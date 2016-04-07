<?php
namespace app\models;

class Upload {
    /**
     * Upload error message
     *
     * @var array
     */
    protected $_message = array (
        1 => 'upload_file_exceeds_limit', 
        2 => 'upload_file_exceeds_form_limit', 
        3 => 'upload_file_partial', 
        4 => 'upload_no_file_selected', 
        6 => 'upload_no_temp_directory', 
        7 => 'upload_unable_to_write_file', 
        8 => 'upload_stopped_by_extension' 
    );
    
    /**
     * Upload config
     *
     * @var array
     */
    protected $_config = array (
        'savePath' => '/tmp', 
        'maxSize' => 0, 
        'maxWidth' => 0, 
        'maxHeight' => 0, 
        'allowedExts' => '*', 
        'allowedTypes' => '*', 
        'override' => false 
    );
    
    /**
     * The num of successfully uploader files
     *
     * @var int
     */
    protected $_num = 0;
    
    /**
     * Formated $_FILES
     *
     * @var array
     */
    protected $_files = array ();
    
    /**
     * Error
     *
     * @var array
     */
    protected $_error;
    
    /**
     * @var unknown_type
     * 1;//文件已存在
     * 2;//文件上传失败
     * 3;//文件类型错误
     * 4;//文件扩展名错误
     * 5;//文件大小错误
     */
    protected $_errno;
    
    public $error;
    
    protected $uploadFileInfo = array ();
    
    /**
     * Constructor
     *
     * Construct && formate $_FILES
     * @param array $config
     */
    public function __construct($config = array()) {
        $this->_config = array_merge ( $this->_config, $config );
        $this->_config ['savePath'] = rtrim ( $this->_config ['savePath'], DIRECTORY_SEPARATOR );
        $this->_format ();
    }
    
    /**
     * Config
     *
     * Set or get configration
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function config($name = null, $value = null) {
        if (null == $name) {
            return $this->_config;
        }
        
        if (null == $value) {
            return isset ( $this->_config [$name] ) ? $this->_config [$name] : null;
        }
        
        $this->_config [$name] = $value;
        
        return $this;
    }
    
    /**
     * Format $_FILES
     *
     */
    protected function _format() {
        foreach ( $_FILES as $field => $file ) {
            
            if (empty ( $file ['name'] ))
                continue;
            
            if (is_array ( $file ['name'] )) {
                $cnt = count ( $file ['name'] );
                
                for($i = 0; $i < $cnt; $i ++) {
                    if (empty ( $file ['name'] [$i] ))
                        continue;
                    $this->_files [] = array (
                        'field' => $field, 
                        'name' => $file ['name'] [$i], 
                        'type' => $file ['type'] [$i], 
                        'tmp_name' => $file ['tmp_name'] [$i], 
                        'error' => $file ['error'] [$i], 
                        'size' => $file ['size'] [$i], 
                        'ext' => $this->getExt ( $file ['name'] [$i], true ) 
                    );
                }
            
            } else {
                $this->_files [] = $file + array (
                    'field' => $field, 
                    'ext' => $this->getExt ( $file ['name'], true ) 
                );
            }
        }
    }
    
    /**
     * Save uploaded files
     *
     * @param array $file
     * @param string $name
     * @return boolean
     */
    public function save($file = null, $name = null) {
        if (! is_null ( $file )) {
            return $this->_move ( $file, $name );
        }
        
        $return = true;
        
        foreach ( $this->_files as $file ) {
            $return = $return && $this->_move ( $file );
        }
        
        return $return;
    }
    
    public function saveOne($name = null) {
        $file = $this->_files [0];
        $ext = $file ['ext'];
        $name = $name . $ext;
        $file ['savepath'] = $this->_config ['savePath'];
        $file ['savename'] = $name;
        $this->uploadFileInfo = $file;
        return $this->_move ( $file, $name );
    }
    
    /**
     * Move file
     *
     * @param array $file
     * @param string $name
     * @return boolean
     */
    protected function _move($file, $name = null) {
        if (! $this->check ( $file )) {
            return false;
        }
        
        if (null === $name)
            $name = $file ['name'];
        $fileFullName = $this->_config ['savePath'] . DIRECTORY_SEPARATOR . $name;
        
        if (file_exists ( $fileFullName ) && ! $this->_config ['override']) {
            $msg = 'file_already_exits:' . $fileFullName;
            $this->_error [] = $msg;
            $this->_errno = 1; //文件已存在
            return false;
        }
        
        $dir = dirname ( $fileFullName );
        is_dir ( $dir ) || $this->mkdir ( $dir );
        
        if (is_writable ( $dir ) && move_uploaded_file ( $file ['tmp_name'], $fileFullName )) {
            $this->_num ++;
            return true;
        }
        
        $this->_error [] = 'move_uploaded_file_failed:' . $dir . 'may not be writeable.';
        return false;
    }
    
    /**
     * Check file
     *
     * @param array $file
     * @return string
     */
    public function check($file) {
        if (UPLOAD_ERR_OK != $file ['error']) {
            $this->_error [] = $this->_message [$file ['error']] . ':' . $file ['name'];
            return false;
        }
        
        if (! is_uploaded_file ( $file ['tmp_name'] )) {
            $this->_error [] = 'file_upload_failed:' . $file ['name'];
            $this->_errno = 2; //文件上传失败
            return false;
        }
        
        if (! $this->checkType ( $file, $this->_config ['allowedTypes'] )) {
            $this->_error [] = 'file_type_not_allowed:' . $file ['name'];
            $this->_errno = 3; //文件类型错误
            return false;
        }
        
        if (! $this->checkExt ( $file, $this->_config ['allowedExts'] )) {
            $this->_error [] = 'file_ext_not_allowed:' . $file ['name'];
            $this->_errno = 4; //文件扩展名错误
            return false;
        }
        
        if (! $this->checkFileSize ( $file, $this->_config ['maxSize'] )) {
            $this->_error [] = 'file_size_not_allowed:' . $file ['name'];
            $this->_errno = 5; //文件大小错误
            return false;
        }
        
        if ($this->isImage ( $file ) && ! $this->checkImageSize ( $file, array (
            $this->_config ['maxWidth'], 
            $this->_config ['maxHeight'] 
        ) )) {
            $this->_error [] = 'image_size_not_allowed:' . $file ['name'];
            return false;
        }
        
        return true;
    }
    
    /**
     * Get image size
     *
     * @param string $file
     * @return array like array(x, y),x is width, y is height
     */
    public function getImageSize($name) {
        if (function_exists ( 'getimagesize' )) {
            $size = getimagesize ( $name );
            return array (
                $size [0], 
                $size [1] 
            );
        }
        
        return false;
    }
    
    /**
     * Get file extension
     *
     * @param string $fileName
     * @return string
     */
    public function getExt($name, $withdot = false) {
        $pathinfo = pathinfo ( $name );
        if (isset ( $pathinfo ['extension'] )) {
            return ($withdot ? '.' : '') . $pathinfo ['extension'];
        }
        return '';
    }
    
    /**
     * Check if is image
     *
     * @param string $type
     * @param string $imageTypes
     * @return boolean
     */
    public function isImage($file) {
        return 'image' == substr ( $file ['type'], 0, 5 );
    }
    
    /**
     * Check file type
     *
     * @param string $type
     * @param string $allowedTypes
     * @return boolean
     */
    public function checkType($file, $allowedTypes) {
        return ('*' == $allowedTypes || false !== stripos ( $allowedTypes, $file ['type'] )) ? true : false;
    }
    
    /**
     * Check file ext
     *
     * @param string $ext
     * @param string $allowedExts
     * @return boolean
     */
    public function checkExt($file, $allowedExts) {
        return ('*' == $allowedExts || false !== stripos ( $allowedExts, $this->getExt ( $file ['name'] ) )) ? true : false;
    }
    
    /**
     * Check file size
     *
     * @param int $size
     * @param int $maxSize
     * @return boolean
     */
    public function checkFileSize($file, $maxSize) {
        return 0 === $maxSize || $file ['size'] <= $maxSize;
    }
    
    /**
     * Check image size
     *
     * @param array $size
     * @param array $maxSize
     * @return unknown
     */
    public function checkImageSize($file, $maxSize) {
        $size = $this->getImageSize ( $file ['tmp_name'] );
        return (0 === $maxSize [0] || $size [0] <= $maxSize [0]) && (0 === $maxSize [1] || $size [1] <= $maxSize [1]);
    }
    
    /**
     * Get formated files
     *
     * @return array
     */
    public function files() {
        return $this->_files;
    }
    
    /**
     * Get the num of sucessfully uploaded files
     *
     * @return int
     */
    public function num() {
        return $this->_num;
    }
    
    /**
     * Get upload error
     *
     * @return array
     */
    public function error() {
        return $this->_error;
    }
    public function errno() {
        return $this->_errno;
    }
    
    /**
     * 检查图片真实类型,可避免人为修改后缀名
     * @param unknown_type $image
     * @return string
     */
    public function getImgType($imginfo) {
        $imageType = strtolower ( substr ( image_type_to_extension ( $imginfo [2] ), 1 ) );
        return $imageType;
    }
    
    public function realtypeToExt($realType) {
        switch ($realType) {
            case 'jpeg' :
                $ext = '.jpg';
                break;
            case 'jpg' :
                $ext = '.jpg';
                break;
            case 'gif' :
                $ext = '.gif';
                break;
            case 'png' :
                $ext = '.png';
                break;
            default :
                $ext = '.jpg';
                break;
        }
        return $ext;
    }
    
    /**
     * 获取图像信息
     * 返回值
     * Array(
     * [0] => 100
     * [1] => 100
     * [2] => 3
     * [3] => width="100" height="100"
     * [bits] => 8
     * [mime] => image/png
     * )
     * @param unknown_type $image
     * @return multitype:
     */
    public function getImgInfo($image) {
        $imginfo = getimagesize ( $image );
        if ($imginfo === false) {
            $this->error = "非法图像文件";
        }
        return $imginfo;
    }
    
    /**
     * 上传普通文件
     * @param unknown_type $savePath
     */
    public function upload($savePath) {
    
    }
    
    /**
     * 上传图片
     * @param unknown_type $savePath
     */
    public function uploadImg($savePath, $saveName) {
        if (empty ( $savePath )) {
            $this->error = '保存路径不能为空';
            return false;
        }
        if (empty ( $saveName )) {
            $this->error = '保存文件名不能为空';
            return false;
        }
        // 检查上传目录
        if (! is_dir ( $savePath )) {
            // 尝试创建目录
            if (! mkdir ( $savePath )) {
                $this->error = '上传目录' . $savePath . '不存在';
                return false;
            }
        } else {
            if (! is_writeable ( $savePath )) {
                $this->error = '上传目录' . $savePath . '不可写';
                return false;
            }
        }
        $uploadFile = $this->_files [0];
        $imgInfo = $this->getImgInfo ( $uploadFile ['tmp_name'] );
        if ($imgInfo === false) {
            $this->error = '非法图像文件';
            return false;
        }
        $imgType = $this->getImgType ( $imgInfo );
        $file = array ();
        $file = $uploadFile;
        $file ['img_info'] = $imgInfo;
        $file ['save_path'] = $savePath;
        $file ['save_name'] = $saveName;
        $file ['real_type'] = $imgType; //图片真实类型
        $file ['real_ext'] = $this->realtypeToExt ( $imgType ); //根据真实类型决定扩展名
        return self::saveImg ( $file );
    }
    
    public function saveImg($file) {
        $filename = $file ['save_path'] . $file ['save_name'] . $file ['real_ext'];
        if (! $this->_config ['override'] && is_file ( $filename )) {
            // 不覆盖同名文件
            $this->error = '文件已经存在:' . $filename;
            return false;
        }
        // 如果是图像文件 检测文件格式
        if (! in_array ( strtolower ( $file ['real_type'] ), array (
            'jpg', 
            'jpeg', 
            'png', 
            'gif' 
        ) ) || false === getimagesize ( $file ['tmp_name'] )) {
            $this->error = '非法图像文件';
            return false;
        }
        //其它图像信息检查
        //检查大小限制
        if (! $this->checkFileSize ( $file, $this->_config ['maxSize'] )) {
            $this->error = '文件大小超过限制';
            return false;
        }
        //检查图像宽高限制
        if ($this->isImage ( $file ) && ! $this->checkImageSize ( $file, array (
            $this->_config ['maxWidth'], 
            $this->_config ['maxHeight'] 
        ) )) {
            $this->error = '文件宽度或高度超过限制';
            return false;
        }
        //上传保存
        if (! is_uploaded_file ( $file ['tmp_name'] )) {
            $this->error = '文件上传失败';
            return false;
        }
        if (! move_uploaded_file ( $file ['tmp_name'], $filename )) {
            $this->error = '文件上传保存错误';
            return false;
        }
        $file ['final_name'] = $filename;
        $this->uploadFileInfo = $file;
        return true;
    }
    
    /**
     * 取得上传文件的信息
     * @access public
     * @return array
     */
    public function getUploadFileInfo() {
        return $this->uploadFileInfo;
    }
    
    public function mkdir($dir, $mode = 0755) {
        if (is_dir ( $dir ))
            return true;
        is_dir ( dirname ( $dir ) ) || self::mkdir ( dirname ( $dir ), $mode );
        if (is_writable ( dirname ( $dir ) )) {
            return mkdir ( $dir, $mode );
        } else {
            throw new \Exception ( dirname ( $dir ) . " can not be written" );
        }
    }
}