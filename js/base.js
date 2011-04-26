window.onload = function() {
    var j = function(a, b) {
        var c;
        for (c = 0; c < a.length; c++) b(c, a[c])
    };
	
	//瀹氫箟 浜?鏂规硶骞跺弽鍥炰簡涓€涓湁璇稿鍑芥暟鐨勫璞★紝鍖呮嫭锛歴how,css,hide,addClass,width(),height(),attr,click,val,each,html(閲岄潰鐨剆cript鍧楀彲浠ユ墽琛?,bind,append,empty,removeClass,remove
    window.$ = function(a) {
        var b, c = typeof a;
        if (c == "string") b = document.querySelectorAll(a);
        if (c == "undefined") b = [];
        if (c == "object") b = [a];
        b.css = function(d, e) {
            j(this,
            function(h, f) {
                f.style[d] = e
            });
            return this
        };
        b.show = function() {
            j(this,
            function(d, e) {
                e.style.display = e.tagName == "SPAN" ? "inline": "block"
            });
            return this
        };
        b.hide = function() {
            j(this,
            function(d, e) {
                e.style.display = "none"
            });
            return this
        };
        b.width = function() {
            if (this.length) return this[0].offsetWidth
        };
        b.height = function() {
            if (this.length) return this[0].offsetHeight
        };
        b.attr = function(d, e) {
            if (e) j(this,
            function(h, f) {
                f.setAttribute(d, e)
            });
            else if (this.length) return this[0].getAttribute(d)
        };
        b.click = function(d) {
            if (d) j(this,
            function(e, h) {
                h.onclick = d
            });
            else if (this.length) this[0].onclick();
            return this
        };
        b.val = function(d) {
            if (this.length) if (d) {
                this[0].value = d;
                return this
            } else return this[0].value
        };
        b.each = function(d) {
            j(this, d);
            return this
        };
        b.html = b.text = function(d) {
            j(this,
            function(e, h) {
                h.innerHTML = d;
                var f = RegExp(/<script.*?>([\s\S]+?)<\/script>/g);
                f.lastIndex = 0;
                for (var g; (g = f.exec(d)) != null;) try {
                    eval(g[1])
                } catch(n) {
                    alert(n)
                }
            });
            return this
        };
        b.addClass = function(d) {
            j(this,
            function(e, h) {
                var f;
                f = h.className.split(" ");
                f.indexOf(d) == -1 && f.push(d);
                f = f.join(" ");
                h.className = f
            });
            return this
        };
        b.removeClass = function(d) {
            j(this,
            function(e, h) {
                var f;
                f = h.className.split(" ");
                var g;
                if ((g = f.indexOf(d)) != -1) f[g] = "";
                f = f.join(" ");
                h.className = f
            });
            return this
        };
        b.hasClass = function(d) {
            if (this.length) return this[0].className.split(" ").indexOf(d) != -1
        };
        b.empty = function() {
            b.html("")
        };
        b.remove = function() {
            j(this,
            function(d, e) {
                e.parentNode.removeChild(e)
            });
            return this
        };
        b.bind = function(d, e) {
            j(this,
            function(h, f) {
                f["on" + d] = e
            });
            return this
        };
        b.append = function(d) {
            j(this,
            function(e, h) {
                h.appendChild(d)
            });
            return this
        };
        b.replaceWith = function(d) {
            if (typeof d == "string") {
                var e = document.createElement("span");
                e.innerHTML = d;
                d = e
            }
            e = window.scrollY;
            j(this,
            function(h, f) {
                var g = f.nextSibling,
                n = f.parentNode;
                g ? n.insertBefore(d, g) : n.appendChild(d);
                n.removeChild(f)
            });
            window.scroll(0, e)
        };
        return b
    };

	//濡傛灉鏄痑ndroid绯荤粺锛屽垯灏哸ndroid瀹㈡埛绔笅杞界殑骞垮憡鏉℃樉绀哄嚭鏉?
    navigator.userAgent.indexOf("Android") != -1 && $("#install-android-entry").show();


///////////////////////////////////////////ajax & history start///////////////////////////////////////////////////////////////////////
    var u = function() {},
    B = function(a) {
        var b, c = [];
        for (b in a) c.push((encodeURIComponent(b) + "=" + encodeURIComponent(a[b])).replace("%20", "+"));
        return c.join("&")
    },
    E = function(a, b) {
        if (b) for (var c in b) a[c] = b[c]
    },
    F = function() {
        var a = $("#loading-box").show();
        a.css("left", (document.body.clientWidth - a.width()) / 2 + "px");
        a.css("top", scrollY + 100 + "px")
    },
			//ajax瀵硅薄
    G = function(a) {
        var b = {
            type: "GET",
            timeout: 0
        };
        E(b, a);
        var c = false,
        d, e;
        if (window.XMLHttpRequest) e = new window.XMLHttpRequest;
        else a: {
            try {
                e = new window.ActiveXObject("Microsoft.XMLHTTP");
                break a
            } catch(h) {}
            e = void 0
        }
        var f = e;
        if (f) { 
            if (b.data && typeof b.data !== "string") b.data = B(b.data);
            if (b.data && b.data.length > 80) b.type = "POST";
            var g = f.onreadystatechange = function(l) {
                if (!f || f.readyState === 0 || l === "abort") {
                    c = true;
                    $("#loading-box").hide();
                    if (f) f.onreadystatechange = u
                } else if (!c && f && (f.readyState === 4 || l === "timeout")) {
                    c = true;
                    $("#loading-box").hide();
                    f.onreadystatechange = u;
                    var o;
                    if (typeof l == "string") o = l;
                    else {
                        a: {
                            try {
                                o = !f.status && location.protocol === "file:" || f.status >= 200 && f.status < 300 || f.status === 304 || f.status === 1223 || f.status === 0;
                                break a
                            } catch(N) {}
                            o = false
                        }
                        o = o ? "success": "error"
                    }
                    status = o;
                    if (status === "success") {
                        d = f.responseText;
                        if (f.getResponseHeader("X-Jiepang-Auth-Fail")) b.authfail && b.authfail(f.getResponseHeader("X-Jiepang-Auth-Fail"));
                        else b.success && b.success(d)
                    }
                    l === "timeout" && f.abort();
                    f = null
                }
            };
            f.open(b.type, b.url + (b.type === "GET" ? (b.url.indexOf("?") == -1 ? "?": "&") + b.data: ""), 1);
            b.type === "POST" && f.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            f.send(b.type === "POST" || b.type === "PUT" || b.type === "DELETE" ? b.data: null);
            try {
                var n = f.abort;
                f.abort = function() {
                    g("abort");
                    f && n.call(f)
                }
            } catch(q) {}
            F();
            b.timeout > 0 && setTimeout(function() {
                f && !c && g("timeout")
            },
            b.timeout);
            return f
        }
    },
    r = function(a, b, c) {
        if (typeof b != "undefined") {
            c = c || {};
            if (b === null) {
                b = "";
                c.expires = -1
            }
            var d = "";
            if (c.expires && (typeof c.expires == "number" || c.expires.toUTCString)) {
                if (typeof c.expires == "number") {
                    d = new Date;
                    d.setTime(d.getTime() + c.expires * 864E5)
                } else d = c.expires;
                d = "; expires=" + d.toUTCString()
            }
            c = c.path ? "; path=" + c.path: "";
            document.cookie = [a, "=", encodeURIComponent(b), d, c].join("")
        } else {
            b = null;
            if (document.cookie && document.cookie != "") {
                c = document.cookie.split(";");
                for (d = 0; d < c.length; d++) {
                    var e = (c[d] || "").replace(/^(\s|\u00A0)+|(\s|\u00A0)+$/g, "");
                    if (e.substring(0, a.length + 1) == a + "=") {
                        b = decodeURIComponent(e.substring(a.length + 1));
                        break
                    }
                }
            }
            return b
        }
    };
//	jiepang.lang = function(a) {
//        r("JIEPANGLANG", a, {
//            expires: 30
//        });
//        location.replace(location.href)
//    };
//    jiepang.latlng = "";

///////////////////////////////////////////////////////geoloaction start////////////////////////////////////////////////////////////////////
//    var v = navigator.geolocation ? navigator.geolocation: window.google && google.gears ? google.gears.factory.create("beta.geolocation") : null,
//    t = null,
//    s = {
//        maximumAge: 2E9,
//        gearsRequestAddress: true,
//        gearsAddressLanguage: "zh-CN",
//        gearsLocationProviderUrls: ["http://www.google.cn/loc/json"],
//        enableHighAccuracy: false,
//        timeout: 0
//    },
//    H = function(a) {
//        if (a.coords) {
//            s.enableHighAccuracy && $("#location-bar").text("宸叉壘鍒版偍鐨勪綅缃?);
//            jiepang.latlng = a.coords.latitude + "," + a.coords.longitude + "," + (a.coords.accuracy ? a.coords.accuracy / 3 : 100);
//            jiepang.lat = a.coords.latitude;
//            jiepang.lon = a.coords.longitude;
//            jiepang.city = "";
//            if (a.gearsAddress) jiepang.city = a.gearsAddress.city;
//            if (s.enableHighAccuracy) {
//                jiepang.ha_count++;
//                jiepang.ha_count == 1 && $("input.afterlocation").each(function(b, c) {
//                    eval(c.value)
//                })
//            } else {
//                s.enableHighAccuracy = true;
//                s.maximumAge = 0;
//                s.timeout = 3E4;
//                y();
//                w()
//            }
//        }
//    },
//    I = function(a) {
//        if (!jiepang.latlng) if (a.code == a.TIMEOUT) {
//            s.timeout = 3E4;
//            y();
//            w()
//        } else $("#location-bar").text("鏃犳硶鎵惧埌鎮ㄧ殑浣嶇疆锛岃閫€鍑哄悗閲嶈瘯")
//    },
//    y = function() {
//        if (v && t) {
//            v.clearWatch(t);
//            t = null
//        }
//    },
//    w = function() {
//        if (v && !t) t = v.watchPosition(H, I, s)
//    },
//    C = null;
//    w();
//    window.onscroll = window.onclick = function() {
//        clearTimeout(C);
//        C = setTimeout(y, 6E4);
//        w()
//    };
	////////////////////////////////////////////////////////////Chrome  鍦扮悊浣嶇疆瀹氫綅////////////////////////////////////////////////////////////

    var z = null,
    x = {};
    window.urlhistory = [];
    var p = function() {
        x = {};
        urlhistory = [];
        $("#goback").hide()
    };
    window.flushhistory = p;
    var i = window.ajax = function(a, b, c, d) {
        z && z.abort();
        if (typeof b == "function") {
            d = c;
            c = b;
            b = {}
        }
//        b.latlng = jiepang.latlng;
//        b.ha_count = jiepang.ha_count;
        b = B(b);
        var e = a + ":" + b;
        if (d && typeof x[e] != "undefined") c(x[e]);
        else {
//            window.pageTracker && pageTracker._trackPageview(a);
            z = G({
                url: a,
                data: b,
                success: function(h) {
                    typeof c == "function" && c(h);
                    x[e] = h;
//                    r("PHPSESSID") && window.localStorage && window.localStorage.setItem("PHPSESSID", r("PHPSESSID"))
                },
                error: function(h, f) {
                    alert(f)
                },
                authfail: function(h) {
//                    if (h == 26) if (a == "action.php?a=login") alert("鐢ㄦ埛鍚嶆垨瀵嗙爜閿欒銆?);
//                    else {
//                        alert("鐢ㄦ埛鍚嶆垨瀵嗙爜閿欒锛岃閲嶆柊鐧诲綍");
//                        logout()
//                    } else {
//                      h == 22 ? alert("姝?Email 杩樻湭婵€娲伙紝璇蜂娇鐢ㄧ敤鎴峰悕鐧诲綍銆?) : alert("璇烽噸鏂扮櫥褰?);
//                      logout()
//                    }
                }
            })
        }
    };

	//ajaxReplace('action.php?a=signup')
    window.ajaxReplace = function(a, b, c, d) {
        if (typeof b != "object") {
            d = c;
            c = b;
            b = {}
        }
        i(a, b,
        function(e) {
            window.gobackhook = null;
            if (urlhistory.length > 0 && urlhistory[urlhistory.length - 1][0] == a) urlhistory[urlhistory.length - 1][1] = b;
            else urlhistory.push([a, b]);
            urlhistory.length > 1 ? $("#goback").show() : $("#goback").hide();
            $("#main").html(e);
            var h = $("ul#subnav span").each(function(f, g) {
                g.onclick = function() {
                    $("ul#subnav span").removeClass("selected");
                    $(g).addClass("selected");
                    $("#main .tab-page").hide();
                    $("#main #" + $(g).attr("showid")).show()
                }
            });
            $(h[0]).click();
            $("input.afterrender").each(function(f, g) {
                eval(g.value)
            });
            typeof c == "function" && c(e);
            $(".what").css("width", document.body.clientWidth - 80 + "px")
        },
        d)
    };
    window.goback = function(a) {
        var b, c;
        if (typeof window.gobackhook == "function") {
            a = window.gobackhook;
            window.gobackhook = null;
            a();
            urlhistory.length <= 1 && $("#goback").hide()
        } else {
            urlhistory.pop();
            b = urlhistory.pop();
            if (typeof b == "function") b();
            else {
                c = b[1];
                b = b[0];
                ajaxReplace(b, c, u, !a)
            }
        }
    };

    window.urlhistory_add = function(a) {
        urlhistory.push(a);
        $("#goback").show()
    };
///////////////////////////////////////////////////////////AJAX & HISTORY END///////////////////////////////////////////////////////////////////////////////////////////////////
//
    window.h = function(a) {
        if (typeof a == "string") {
            a = a.replace(/&/g, "&amp;");
            a = a.replace(/"/g, "&quot;");
            a = a.replace(/'/g, "&#039;");
            a = a.replace(/</g, "&lt;");
            a = a.replace(/>/g, "&gt;")
        }
        return a
    };
    window.nl2br = function(a) {
        if (typeof a == "string") a = a.replace(/\n/g, "<br>").replace(/\r/g, "");
        return a
    };
    window.friendlyTime = function(a) {
        var b = RegExp(/(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)/);
        b.lastIndex = 0;
        b = b.exec(a);
        a = new Date;
        a.setUTCFullYear(b[1]);
        a.setUTCMonth(b[2] - 1);
        a.setUTCDate(b[3]);
        a.setUTCHours(b[4]);
        a.setUTCMinutes(b[5]);
        a.setUTCSeconds(b[6]);
        a.setUTCMilliseconds(0);
        b = new Date;
        a.setTime(a.getTime() + 288E5);
        b.setTime(b.getTime() + 288E5);
        var c = [[1, "绉?], [60, "鍒嗛挓"], [3600, "灏忔椂"]],
        d,
        e = (b.getTime() - a.getTime()) / 1E3;
        e = e > 0 ? e: 1;
        var h = "";
        for (d = 0; d < c.length; d++) if (c[d + 1] && e < c[d + 1][0]) {
            h += Math.round(e / c[d][0]) + " " + c[d][1];
            break
        }
        if (h != "") {
            if (e >= 3600) h = "绾?" + h;
            h += "鍓?
        } else h = a.getUTCFullYear() == b.getUTCFullYear() && a.getUTCMonth() == b.getUTCMonth() && a.getUTCDate() == b.getUTCDate() ? "浠婂ぉ " + a.getUTCHours() + ":" + (a.getUTCMinutes() < 10 ? "0" + a.getUTCMinutes() : a.getUTCMinutes()) : a.getUTCMonth() + 1 + "鏈? + a.getUTCDate() + "鏃?" + a.getUTCHours() + ":" + (a.getUTCMinutes() < 10 ? "0" + a.getUTCMinutes() : a.getUTCMinutes());
        return h
    };
};