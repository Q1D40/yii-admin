/*
 Date: 2014-01-21 
 */
function login() {
    return window.location.href = "http://localhost/dangjia/index.php/acc/login?returnUrl=" + escape(location.href).replace(/\//g, "%2F"), !1
}
function createCookie(a, b, c, d) {
    var d = d ? d : "/";
    if (c) {
        var e = new Date;
        e.setTime(e.getTime() + 1e3 * 60 * 60 * 24 * c);
        var f = "; expires=" + e.toGMTString()
    } else var f = "";
    document.cookie = a + "=" + b + f + "; path=" + d
}
function readCookie(a) {
    for (var b = a + "=", c = document.cookie.split(";"), d = 0; d < c.length; d++) {
        for (var e = c[d]; " " == e.charAt(0);)e = e.substring(1, e.length);
        if (0 == e.indexOf(b))return e.substring(b.length, e.length)
    }
    return null
}

function str_replace(search, replace, subject, count) {
    var i = 0,
        j = 0,
        temp = '',
        repl = '',
        sl = 0,
        fl = 0,
        f = [].concat(search),
        r = [].concat(replace),
        s = subject,
        ra = Object.prototype.toString.call(r) === '[object Array]',
        sa = Object.prototype.toString.call(s) === '[object Array]';
    s = [].concat(s);

    if (typeof(search) === 'object' && typeof(replace) === 'string') {
        temp = replace;
        replace = new Array();
        for (i = 0; i < search.length; i += 1) {
            replace[i] = temp;
        }
        temp = '';
        r = [].concat(replace);
        ra = Object.prototype.toString.call(r) === '[object Array]';
    }

    if (count) {
        this.window[count] = 0;
    }

    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j = 0, fl = f.length; j < fl; j++) {
            temp = s[i] + '';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp)
                .split(f[j])
                .join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length - s[i].length) / f[j].length;
            }
        }
    }
    return sa ? s : s[0];
}

function isEmail(email) {
	//var patten = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
	var patten = /^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
	return !patten.exec(email) ? false : true;
}

function isMobile(mobile) {
	// var patten = /^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/;
	var patten = /^1\d{10}$/;
	return !patten.exec(mobile) ? false : true;
}

function captchachg() {
	$("#captcha_img").attr('src', $CONFIG['uroot'] + "acc/captcha?" + Math.random());
}

function urlencode(str) {
	str = (str + '').toString();
	return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27')
			.replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A')
			.replace(/%20/g, '+');
}