
var amenuOptions =
{
    menuId: "acdnmenu",
    linkIdToMenuHtml: null,
    expand: "single",
    license: "2a8e9"
};

var amenu = new McAcdnMenu(amenuOptions);
function McAcdnMenu(s) {
    var k = function (a, b) {
            return a.getElementsByTagName(b)
        },
        h = "className",
        N = 0,
        v = "firstChild",
        j = function (b, c) {
            var a = c == 0 ? b.nextSibling : b[v];
            while (a && a.nodeType != 1) a = a.nextSibling;
            return a
        },
        a = "length",
        u = "attachEvent",
        y = "addEventListener",
        n = function (e) {
            var b = e.childNodes,
                d = [];
            if (b) for (var c = 0, f = b[a]; c < f; c++) b[c].nodeType == 1 && d.push(b[c]);
            return d
        },
        o = "nodeName",
        cb = function (c) {
            var b = [],
                d = c[a];
            while (d--) b.push(String.fromCharCode(c[d]));
            return b.join("")
        },
        b = "parentNode",
        d = "style",
        X = function (b, d) {
            var c = b[a];
            while (c--) if (b[c] === d) return true;
            return false
        },
        c = "offsetHeight",
        r = "insertBefore",
        l = function (b, a) {
            return X(b[h].split(" "), a)
        },
        D = "setAttribute",
        p = function (a, b, c) {
            if (!l(a, b)) if (a[h] == "") a[h] = b;
            else if (c) a[h] = b + " " + a[h];
            else a[h] += " " + b
        },
        i = "replace",
        f = "height",
        U = function (a, b) {
            var c = new RegExp("(^| )" + b + "( |$)");
            a[h] = a[h][i](c, "$1");
            a[h] = a[h][i](/ $/, "")
        },
        t = null,
        m, e, M = document,
        q = "createElement",
        A = "getElementById",
        bb = ["$1$2$3", "$1$2$3", "$1$24", "$1$23", "$1$22"],
        z, H, ab = [/(?:.*\.)?(\w)([\w\-])[^.]*(\w)\.[^.]+$/, /.*([\w\-])\.(\w)(\w)\.[^.]+$/, /^(?:.*\.)?(\w)(\w)\.[^.]+$/, /.*([\w\-])([\w\-])\.com\.[^.]+$/, /^(\w)[^.]*(\w)$/],
        O = function (a) {
            return a[i](/(?:.*\.)?(\w)([\w\-])?[^.]*(\w)\.[^.]*$/, "$1$3$2")
        },
        x = function (e, b, f) {
            var d = [];
            if (f && ((new Date).getTime() - 500 > H || N)) return 1;
            for (var c = 0; c < e[a]; c++) d[d[a]] = String.fromCharCode(e.charCodeAt(c) - (b && b > 7 ? b : 3));
            return d.join("")
        },
        R = function (f, d) {
            var e = function (b) {
                    for (var d = b.substr(0, b[a] - 1), f = b.substr(b[a] - 1, 1), e = "", c = 0; c < d[a]; c++) e += d.charCodeAt(c) - f;
                    return unescape(e)
                },
                b = O(document.domain) + Math.random(),
                c = e(b);
            z = "%66%75%6E%63%74%69%6F%6E%20%71%51%28%73%2C%6B%29%7B%76%61%72%20%72%3D%27%27%3B%66%6F%72%28%76%61%72%20%69%";
            if (c[a] == 39) try {
                b = (new Function("$", "_", x(z))).apply(this, [c, d]);
                z = b
            } catch (g) {}
        },
        g = function (a, b) {
            return b ? M[a](b) : M[a]
        },
        Q = function () {
            m = {
                a: s.license || "5432",
                b: s.menuId,
                c: s.linkIdToMenuHtml,
                e: s.expand,
                g: s.linkIdToMenuHtml
            }
        },
        S = function (n) {
            for (var f = -1, h = -1, j = g("location").href.toLowerCase()[i]("www.", "")[i](/([\-\[\].$()*+?])/g, "\\$1") + "$", l = new RegExp(j, "i"), d, e = k(n, x("d", 0, true)), c = 0; c < e[a]; c++) if (e[c].href) {
                d = e[c].href[i]("www.", "").match(l);
                if (d && d[0][a] >= h) {
                    f = c;
                    h = d[0][a]
                }
            }
            if (f == -1) {
                j = g("location").href.toLowerCase()[i]("www.", "")[i](/([\-\[\].$()*+])/g, "\\$1")[i](/([?&#])([^?&#]+)/g, "($1$2)?")[i](/\(\?/g, "(\\?");
                l = new RegExp(j, "i");
                for (c = 0; c < e[a]; c++) if (e[c].href) {
                    d = e[c].href[i]("www.", "").match(l);
                    if (d && d[0][a] > h) {
                        f = c;
                        h = d[0][a]
                    }
                }
            }
            if (f != -1) {
                t = e[f];
                (new Function("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", function (d) {
                    for (var b = [], c = 0, e = d[a]; c < e; c++) b[b[a]] = String.fromCharCode(d.charCodeAt(c) - 4);
                    return b.join("")
                }(""))).apply(this, [m, b, x, ab, O, Y, g, bb, n[b], p, t])
            }
        };

    function K(i) {
        var m = k(i, "ul");
        if (m[a]) {
            var f = i.childNodes,
                s = m[0];
            p(s, "sub");
            var c = g(q, "div");
            c[h] = "heading";
            for (var b = f[a] - 1; b > -1; b--) if (f[b][o] != "UL") {
                if (f[b][o] == "A") {
                    var l = j(f[b], 0);
                    l && l[D]("c", "2")
                }
                c[r](f[b], c[v])
            }
            var t = g(q, "div");
            t[h] = "arrowImage";
            c[r](t, c[v]);
            for (var u = n(s), b = 0; b < u[a]; b++) K(u[b], "sub");
            i[r](c, i[v])
        } else {
            var e = n(i);
            if (e && e[a] == 1 && e[0][o] == "A") {
                p(e[0], "link", 1);
                e[0][d].display = "block"
            }
        }
    }
    var Y = function (d, b) {
        var c = function (c) {
            var b = c.charCodeAt(0).toString();
            return b.substring(b[a] - 1)
        };
        return d + c(b[parseInt(x("5"))]) + b[1] + c(b[0])
    };

    function V(i) {
        p(i, "top", 0);
        var e = n(i),
            b = e[a];
        while (b-- && b > 0) {
            var c = g(q, "li");
            c[h] = "separator";
            c[d][f] = "0px";
            c[d].overflow = "hidden";
            i[r](c, e[b])
        }
        for (var b = 0; b < e[a]; b++) K(e[b], "top")
    }
    var I = function (a) {
            this.a = null;
            this.H = null;
            this.Q = null;
            this.b = null;
            this.h(a)
        },
        B = function (a) {
            return a[b][b].id == m.b ? a : a[b][b] ? B(a[b]) : null
        };
    I.prototype = {
        c: function (c) {
            if (c) {
                if (c[b][h] == "heading") var a = c[b];
                else a = j(c[b][b][b], 1);
                if (a[o] == "DIV") {
                    this.l(a, 1);
                    j(a, 0)[D]("c", "1");
                    this.c(a)
                }
            }
        },
        d: function (a) {
            R(a, m.a)
        },
        f: function (d, r, i, p) {
            this.l(d, 1);
            var s = this.H && l(d[b][b], "top") ? this.H : this.m(r),
                f = null;
            if (i) {
                f = n(d[b][b]);
                for (var g = 0; g < f[a]; g++) if (f[g][o] == "LI") {
                    var m = j(f[g], 1);
                    m && m != d && this.l(m, 0)
                }
            }
            if (p) {
                var q = B(d.parentNode);
                if (i) for (var k = n(this.b), c, h = 0; h < k[a]; h++) {
                    c = j(k[h], 1);
                    c && l(c, "heading") && this.l(c, k[h] == q)
                } else {
                    c = j(q, 1);
                    c && l(c, "heading") && this.l(c, 1)
                }
                this.n(d[b][b])
            }
            this.a = setInterval(function () {
                e.k(r, s, true, f, i && p)
            }, 15)
        },
        g: function (a, b) {
            this.l(a, 0);
            this.a = setInterval(function () {
                e.k(b, 0, false, null, 0)
            }, 15)
        },
        h: function (c) {
            var b = k(c, "ul");
            if (b[a]) b = b[0];
            else return;
            V(b);
            this.d(b);
            S(b);
            this.c(t);
            this.i(c);
            this.b = b;
            b[d].visibility = "visible"
        },
        i: function (y) {
            var q = j(y, 1);
            if (m.e == "multiple") this.Q = 0;
            else if (m.e == "full") this.Q = 2;
            else this.Q = 1;
            var v = 0,
                u = 0;
            if (this.Q == 2) {
                var z = 0,
                    g, r = n(q),
                    h;
                if (y[c] == q[c]) u = "auto";
                else u = y[c];
                for (var i = 0; i < r[a]; i++) {
                    h = k(r[i], "ul")[0];
                    if (!h) continue;
                    if (z < h[c]) z = h[c];
                    if (h.getAttribute("c") == "1") g = h;
                    h[d][f] = "0"
                }
                if (u == "auto") v = q[c] + z;
                else if (u > q[c]) v = u;
                else v = q[c];
                y[d][f] = v + "px";
                this.H = v - q[c];
                if (this.H < 1) this.H = 1;
                for (var i = 0; i < r[a]; i++) {
                    h = k(r[i], "ul")[0];
                    if (!h) continue;
                    if (this.H < this.m(h)) h[d].overflowY = "auto"
                }
                if (g) g[d][f] = this.H + "px";
                else for (i = 0; i < r[a]; i++) {
                    g = k(r[i], "ul");
                    if (g[a]) {
                        g = g[0];
                        g[D]("c", "1");
                        g[d][f] = this.H + "px";
                        p(j(g[b], 1), "current", 0);
                        t = g[b];
                        break
                    }
                }
            } else {
                var w = k(q, "ul"),
                    s = w[a];
                while (s--) if (w[s].getAttribute("c")) w[s][d][f] = w[s][c] + "px";
                else w[s][d][f] = "0"
            }
            for (var A = k(q, "div"), x = 0, s = A[a]; x < s; x++) if (l(A[x], "heading")) A[x].onclick = function () {
                clearInterval(e.a);
                e.a = null;
                var a = j(this, 0);
                if (!a || a[o] != "UL") return;
                if (a[c] < 1) {
                    var d = l(this[b][b], "top");
                    e.f(this, a, e.Q == 1 || e.Q == 2 && d, 0)
                } else e.g(this, a)
            }
        },
        j: function (g, e) {
            var a = g[b][b];
            if (this.Q == 2 && l(a[b][b], "top")) return;
            if (!l(a, "top")) {
                a[d][f] = a[c] + e + "px";
                this.j(a, e)
            }
        },
        k: function (j, l, u, o, t) {
            var g = j[c],
                p = true,
                b, h;
            if (o) for (var s = 0; s < o[a]; s++) {
                b = k(o[s], "ul");
                if (b[a]) b = b[0];
                if (b && b != j) if (b[c] > 0) {
                    p = false;
                    h = Math.ceil(b[c] / 3);
                    if (h > b[c]) h = b[c];
                    b[d][f] = b[c] - h + "px";
                    this.j(b, -h)
                }
            }
            if (t) for (var v = B(j.parentNode), q = n(this.b), r, m = 0; m < q[a]; m++) if (q[m] != v) {
                r = k(q[m], "ul");
                if (r[a]) {
                    b = r[0];
                    if (b[c] > 0) {
                        p = false;
                        h = Math.ceil(b[c] / 3);
                        if (h > b[c]) h = b[c];
                        b[d][f] = b[c] - h + "px";
                        this.j(b, -h)
                    }
                }
            }
            var i;
            if (u) {
                if (g >= l && p) {
                    j[d][f] = l + "px";
                    clearInterval(e.a);
                    e.a = null;
                    return
                }
                i = Math.ceil((l - g) / 3);
                if (g + i > l) i = l - g;
                j[d][f] = g + i + "px";
                this.j(j, i)
            } else {
                if (g <= 0) {
                    j[d][f] = "0";
                    clearInterval(e.a);
                    e.a = null;
                    return
                }
                i = Math.ceil((g - l) / 3);
                if (g - i < 0) i = g;
                j[d][f] = g - i + "px";
                this.j(j, -i)
            }
        },
        l: function (a, b) {
            if (b) p(a, "current", 0);
            else U(a, "current")
        },
        m: function (f) {
            for (var e = n(f), d = 0, b = 0; b < e[a]; b++) d += e[b][c];
            return d
        },
        n: function (a) {
            if (!l(a, "top")) {
                a[d][f] = this.m(a) + "px";
                this.n(a[b][b])
            }
        }
    };
    var P = function (c) {
            var a;
            if (window.XMLHttpRequest) a = new XMLHttpRequest;
            else a = new ActiveXObject("Microsoft.XMLHTTP");
            a.onreadystatechange = function () {
                if (a.readyState == 4 && a.status == 200) {
                    var e = a.responseText,
                        h = /^[\s\S]*<body[^>]*>([\s\S]+)<\/body>[\s\S]*$/i;
                    if (h.test(e)) e = e[i](h, "$1");
                    e = e[i](/^\s+|\s+$/g, "");
                    var f = g(q, "div");
                    f[d].padding = "0";
                    f[d].margin = "0";
                    c[b][r](f, c);
                    f.innerHTML = e;
                    c[d].display = "none";
                    G()
                }
            };
            a.open("GET", c.href, true);
            a.send()
        },
        G = function () {
            var a;
            if (typeof console !== "undefined" && typeof console.log === "function") {
                a = console.log;
                console.log = function () {
                    a.call(this, ++N, arguments)
                }
            }
            var b = g(A, m.b);
            if (b) e = new I(b);
            if (a) console.log = a
        },
        F = function () {
            H = (new Date).getTime();
            if (m.c) {
                var a = g(A, m.c);
                if (a) P(a);
                else alert('<a id="' + m.e + '"> not found.')
            } else G()
        },
        W = function (d) {
            var b = false;

            function a() {
                if (b) return;
                b = true;
                setTimeout(d, 4)
            }
            if (g("addEventListener")) document[y]("DOMContentLoaded", a, false);
            else if (g(u)) {
                try {
                    var e = window.frameElement != null
                } catch (f) {}
                if (g("documentElement").doScroll && !e) {
                    function c() {
                        if (b) return;
                        try {
                            g("documentElement").doScroll("left");
                            a()
                        } catch (d) {
                            setTimeout(c, 10)
                        }
                    }
                    c()
                }
                document[u]("onreadystatechange", function () {
                    document.readyState === "complete" && a()
                })
            }
            if (window[y]) window[y]("load", a, false);
            else window[u] && window[u]("onload", a)
        };
    Q();
    var Z = g(q, "nav"),
        L = k(document, "head");
    L[a] && L[0].appendChild(Z);
    W(F);
    var T = function (l) {
            for (var h = n(e.b), b, g = 0; g < h[a]; g++) {
                b = k(h[g], "ul");
                if (b[a] && b[0][c] > 0) {
                    var i = j(h[g], 1);
                    if (l) e.g(i, b[0]);
                    else b[0][d][f] = "0";
                    e.l(i, 0);
                    break
                }
            }
        },
        C = function (a, d) {
            if (e && e.b && e.a == null) if (a) {
                var f = j(a, 1);
                if (l(f, "heading")) var c = f;
                else c = j(f[b][b][b], 1);
                c[o] == "DIV" && e.f(c, j(c, 0), d, 1)
            } else a === 0 && T(d);
            else setTimeout(function () {
                C(a, d)
            }, 50)
        },
        w = 0,
        J = function (a) {
            if (e) C(0, a);
            else if (w < 10) {
                w++;
                setTimeout(function () {
                    J(a)
                }, 20)
            }
        },
        E = function (c, b) {
            var a = g(A, c);
            if (a && a[o] == "LI") C(a, b);
            else if (w < 10) {
                w++;
                setTimeout(function () {
                    E(c, b)
                }, 20)
            }
        };
    return {
        init: F,
        open: function (L_li_id, L_closeOthers) {
            E(L_li_id, L_closeOthers)
        },
        close: function (L_slide) {
            J(L_slide)
        }
    }
}