/**
 @Name：Kz.layedit 富文本编辑器
 @Author：贤心
 @Modifier:KnifeZ
 @License：MIT
 @Version: V1.0.0 Release
 */
"use strict";

function AddCustomThemes(t, e, i) {
    var n = [];
    return layui.each(t, function (t, l) {
        n.push('<option value="' + e[t] + '"  data-img="' + i[t] + '">' + l + "</option>")
    }),
        ['<li class="layui-form-item" style="position: relative">', '<label class="layui-form-label">主题选择</label>', '<div class="layui-input-block">', '<select name="theme" style="display:block;height:38px;width:100%;">' + n.join("") + "</select>", "</div>", "</li>"].join("")
}

function style_html(t, e, i, n) {
    function l() {
        return this.pos = 0,
            this.token = "",
            this.current_mode = "CONTENT",
            this.tags = {
                parent: "parent1",
                parentcount: 1,
                parent1: ""
            },
            this.tag_type = "",
            this.token_text = this.last_token = this.last_text = this.token_type = "",
            this.Utils = {
                whitespace: "\n\r\t ".split(""),
                single_token: "br,input,link,meta,!doctype,basefont,base,area,hr,wbr,param,img,isindex,?xml,embed".split(","),
                extra_liners: "head,body,/html".split(","),
                in_array: function (t, e) {
                    for (var i = 0; i < e.length; i++) if (t === e[i]) return !0;
                    return !1
                }
            },
            this.get_content = function () {
                for (var t = "", e = [], i = !1;
                     "<" !== this.input.charAt(this.pos);) {
                    if (this.pos >= this.input.length) return e.length ? e.join("") : ["", "TK_EOF"];
                    if (t = this.input.charAt(this.pos), this.pos++, this.line_char_count++, this.Utils.in_array(t, this.Utils.whitespace)) e.length && (i = !0),
                        this.line_char_count--;
                    else {
                        if (i) {
                            if (this.line_char_count >= this.max_char) {
                                e.push("\n");
                                for (var n = 0; n < this.indent_level; n++) e.push(this.indent_string);
                                this.line_char_count = 0
                            } else e.push(" "),
                                this.line_char_count++;
                            i = !1
                        }
                        e.push(t)
                    }
                }
                return e.length ? e.join("") : ""
            },
            this.get_script = function () {
                var t = "",
                    e = [],
                    i = new RegExp("<\/script>", "igm");
                i.lastIndex = this.pos;
                for (var n = i.exec(this.input), l = n ? n.index : this.input.length; this.pos < l;) {
                    if (this.pos >= this.input.length) return e.length ? e.join("") : ["", "TK_EOF"];
                    t = this.input.charAt(this.pos),
                        this.pos++,
                        e.push(t)
                }
                return e.length ? e.join("") : ""
            },
            this.record_tag = function (t) {
                this.tags[t + "count"] ? (this.tags[t + "count"]++, this.tags[t + this.tags[t + "count"]] = this.indent_level) : (this.tags[t + "count"] = 1, this.tags[t + this.tags[t + "count"]] = this.indent_level),
                    this.tags[t + this.tags[t + "count"] + "parent"] = this.tags.parent,
                    this.tags.parent = t + this.tags[t + "count"]
            },
            this.retrieve_tag = function (t) {
                if (this.tags[t + "count"]) {
                    for (var e = this.tags.parent; e && t + this.tags[t + "count"] !== e;) e = this.tags[e + "parent"];
                    e && (this.indent_level = this.tags[t + this.tags[t + "count"]], this.tags.parent = this.tags[e + "parent"]),
                        delete this.tags[t + this.tags[t + "count"] + "parent"],
                        delete this.tags[t + this.tags[t + "count"]],
                        1 == this.tags[t + "count"] ? delete this.tags[t + "count"] : this.tags[t + "count"]--
                }
            },
            this.get_tag = function () {
                var t = "",
                    e = [],
                    i = !1;
                do {
                    if (this.pos >= this.input.length) return e.length ? e.join("") : ["", "TK_EOF"];
                    t = this.input.charAt(this.pos), this.pos++, this.line_char_count++, this.Utils.in_array(t, this.Utils.whitespace) ? (i = !0, this.line_char_count--) : ("'" !== t && '"' !== t || e[1] && "!" === e[1] || (t += this.get_unformatted(t), i = !0), "=" === t && (i = !1), e.length && "=" !== e[e.length - 1] && ">" !== t && i && (this.line_char_count >= this.max_char ? (this.print_newline(!1, e), this.line_char_count = 0) : (e.push(" "), this.line_char_count++), i = !1), e.push(t))
                } while (">" !== t);
                var n, l = e.join("");
                n = -1 != l.indexOf(" ") ? l.indexOf(" ") : l.indexOf(">");
                var a = l.substring(1, n).toLowerCase();
                if ("/" === l.charAt(l.length - 2) || this.Utils.in_array(a, this.Utils.single_token)) this.tag_type = "SINGLE";
                else if ("script" === a) this.record_tag(a),
                    this.tag_type = "SCRIPT";
                else if ("style" === a) this.record_tag(a),
                    this.tag_type = "STYLE";
                else if ("!" === a.charAt(0)) if (-1 != a.indexOf("[if")) {
                    if (-1 != l.indexOf("!IE")) {
                        var o = this.get_unformatted("--\x3e", l);
                        e.push(o)
                    }
                    this.tag_type = "START"
                } else if (-1 != a.indexOf("[endif")) this.tag_type = "END",
                    this.unindent();
                else if (-1 != a.indexOf("[cdata[")) {
                    var o = this.get_unformatted("]]>", l);
                    e.push(o),
                        this.tag_type = "SINGLE"
                } else {
                    var o = this.get_unformatted("--\x3e", l);
                    e.push(o),
                        this.tag_type = "SINGLE"
                } else "/" === a.charAt(0) ? (this.retrieve_tag(a.substring(1)), this.tag_type = "END") : (this.record_tag(a), this.tag_type = "START"),
                this.Utils.in_array(a, this.Utils.extra_liners) && this.print_newline(!0, this.output);
                return e.join("")
            },
            this.get_unformatted = function (t, e) {
                if (e && -1 != e.indexOf(t)) return "";
                var i = "",
                    n = "",
                    l = !0;
                do {
                    if (i = this.input.charAt(this.pos), this.pos++, this.Utils.in_array(i, this.Utils.whitespace)) {
                        if (!l) {
                            this.line_char_count--;
                            continue
                        }
                        if ("\n" === i || "\r" === i) {
                            n += "\n";
                            for (var a = 0; a < this.indent_level; a++) n += this.indent_string;
                            l = !1,
                                this.line_char_count = 0;
                            continue
                        }
                    }
                    n += i, this.line_char_count++, l = !0
                } while (-1 == n.indexOf(t));
                return n
            },
            this.get_token = function () {
                var t;
                if ("TK_TAG_SCRIPT" === this.last_token) {
                    var e = this.get_script();
                    return "string" != typeof e ? e : (t = js_beautify(e, this.indent_size, this.indent_character, this.indent_level), [t, "TK_CONTENT"])
                }
                if ("CONTENT" === this.current_mode) return t = this.get_content(),
                    "string" != typeof t ? t : [t, "TK_CONTENT"];
                if ("TAG" === this.current_mode) {
                    if ("string" != typeof(t = this.get_tag())) return t;
                    return [t, "TK_TAG_" + this.tag_type]
                }
            },
            this.printer = function (t, e, i, n) {
                this.input = t || "",
                    this.output = [],
                    this.indent_character = e || " ",
                    this.indent_string = "",
                    this.indent_size = i || 2,
                    this.indent_level = 0,
                    this.max_char = n || 7e3,
                    this.line_char_count = 0;
                for (var l = 0; l < this.indent_size; l++) this.indent_string += this.indent_character;
                this.print_newline = function (t, e) {
                    if (this.line_char_count = 0, e && e.length) {
                        if (!t) for (; this.Utils.in_array(e[e.length - 1], this.Utils.whitespace);) e.pop();
                        e.push("\n");
                        for (var i = 0; i < this.indent_level; i++) e.push(this.indent_string)
                    }
                },
                    this.print_token = function (t) {
                        this.output.push(t)
                    },
                    this.indent = function () {
                        this.indent_level++
                    },
                    this.unindent = function () {
                        this.indent_level > 0 && this.indent_level--
                    }
            },
            this
    }

    var l, a;
    a = new l,
        a.printer(t, i, e);
    for (var o = !0; ;) {
        var s = a.get_token();
        if (a.token_text = s[0], a.token_type = s[1], "TK_EOF" === a.token_type) break;
        switch (a.token_type) {
            case "TK_TAG_START":
            case "TK_TAG_SCRIPT":
            case "TK_TAG_STYLE":
                a.print_newline(!1, a.output),
                    a.print_token(a.token_text),
                    a.indent(),
                    a.current_mode = "CONTENT";
                break;
            case "TK_TAG_END":
                o && a.print_newline(!0, a.output),
                    a.print_token(a.token_text),
                    a.current_mode = "CONTENT",
                    o = !0;
                break;
            case "TK_TAG_SINGLE":
                a.print_newline(!1, a.output),
                    a.print_token(a.token_text),
                    a.current_mode = "CONTENT";
                break;
            case "TK_CONTENT":
                "" !== a.token_text && (o = !1, a.print_token(a.token_text)),
                    a.current_mode = "TAG"
        }
        a.last_token = a.token_type,
            a.last_text = a.token_text
    }
    return a.output.join("")
}

function js_beautify(t, e, i, n) {
    function l() {
        for (; y.length && (" " === y[y.length - 1] || y[y.length - 1] === T);) y.pop()
    }

    function a(t) {
        if (t = void 0 === t || t, l(), y.length) {
            "\n" === y[y.length - 1] && t || y.push("\n");
            for (var e = 0; e < n; e++) y.push(T)
        }
    }

    function o() {
        var t = y.length ? y[y.length - 1] : " ";
        " " !== t && "\n" !== t && t !== T && y.push(" ")
    }

    function s() {
        y.push(h)
    }

    function d() {
        n++
    }

    function r() {
        n && n--
    }

    function c(t) {
        _.push(b),
            b = t
    }

    function u() {
        S = "DO_BLOCK" === b,
            b = _.pop()
    }

    function f(t, e) {
        for (var i = 0; i < e.length; i++) if (e[i] === t) return !0;
        return !1
    }

    function p() {
        var t = 0,
            e = "";
        do {
            if (A >= m.length) return ["", "TK_EOF"];
            e = m.charAt(A), A += 1, "\n" === e && (t += 1)
        } while (f(e, E));
        if (t > 1) for (var i = 0; i < 2; i++) a(0 === i);
        var n = 1 === t;
        if (f(e, k)) {
            if (A < m.length) for (; f(m.charAt(A), k) && (e += m.charAt(A), (A += 1) !== m.length);) ;
            if (A !== m.length && e.match(/^[0-9]+[Ee]$/) && "-" === m.charAt(A)) {
                A += 1;
                return e += "-" + p(A)[0],
                    [e, "TK_WORD"]
            }
            return "in" === e ? [e, "TK_OPERATOR"] : [e, "TK_WORD"]
        }
        if ("(" === e || "[" === e) return [e, "TK_START_EXPR"];
        if (")" === e || "]" === e) return [e, "TK_END_EXPR"];
        if ("{" === e) return [e, "TK_START_BLOCK"];
        if ("}" === e) return [e, "TK_END_BLOCK"];
        if (";" === e) return [e, "TK_END_COMMAND"];
        if ("/" === e) {
            var l = "";
            if ("*" === m.charAt(A)) {
                if ((A += 1) < m.length) for (;
                    ("*" !== m.charAt(A) || !m.charAt(A + 1) || "/" !== m.charAt(A + 1)) && A < m.length && (l += m.charAt(A), !((A += 1) >= m.length));) ;
                return A += 2,
                    ["/*" + l + "*/", "TK_BLOCK_COMMENT"]
            }
            if ("/" === m.charAt(A)) {
                for (l = e;
                     "\r" !== m.charAt(A) && "\n" !== m.charAt(A) && (l += m.charAt(A), !((A += 1) >= m.length));) ;
                return A += 1,
                n && a(),
                    [l, "TK_COMMENT"]
            }
        }
        if ("'" === e || '"' === e || "/" === e && ("TK_WORD" === g && "return" === v || "TK_START_EXPR" === g || "TK_END_BLOCK" === g || "TK_OPERATOR" === g || "TK_EOF" === g || "TK_END_COMMAND" === g)) {
            var o = e,
                s = !1;
            if (e = "", A < m.length) for (;
                (s || m.charAt(A) !== o) && (e += m.charAt(A), s = !s && "\\" === m.charAt(A), !((A += 1) >= m.length));) ;
            return A += 1,
            "TK_END_COMMAND" === g && a(),
                [o + e + o, "TK_STRING"]
        }
        if (f(e, w)) {
            for (; A < m.length && f(e + m.charAt(A), w) && (e += m.charAt(A), !((A += 1) >= m.length));) ;
            return [e, "TK_OPERATOR"]
        }
        return [e, "TK_UNKNOWN"]
    }

    var m, y, h, g, v, x, b, _, T, E, k, w, A, C, N, K, O, S, z, L;
    for (i = i || " ", e = e || 4, T = ""; e--;) T += i;
    for (m = t, x = "", g = "TK_START_EXPR", v = "", y = [], S = !1, z = !1, L = !1, E = "\n\r\t ".split(""), k = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_$".split(""), w = "+ - * / % & ++ -- = += -= *= /= %= == === != !== > < >= <= >> << >>> >>>= >>= <<= && &= | || ! !! , : ? ^ ^= |=".split(" "), C = "continue,try,throw,return,var,if,switch,case,default,for,while,break,function".split(","), b = "BLOCK", _ = [b], n = n || 0, A = 0, N = !1; ;) {
        var I = p(A);
        if (h = I[0], "TK_EOF" === (O = I[1])) break;
        switch (O) {
            case "TK_START_EXPR":
                z = !1,
                    c("EXPRESSION"),
                "TK_END_EXPR" === g || "TK_START_EXPR" === g || ("TK_WORD" !== g && "TK_OPERATOR" !== g ? o() : f(x, C) && "function" !== x && o()),
                    s();
                break;
            case "TK_END_EXPR":
                s(),
                    u();
                break;
            case "TK_START_BLOCK":
                c("do" === x ? "DO_BLOCK" : "BLOCK"),
                "TK_OPERATOR" !== g && "TK_START_EXPR" !== g && ("TK_START_BLOCK" === g ? a() : o()),
                    s(),
                    d();
                break;
            case "TK_END_BLOCK":
                "TK_START_BLOCK" === g ? (l(), r()) : (r(), a()),
                    s(),
                    u();
                break;
            case "TK_WORD":
                if (S) {
                    o(),
                        s(),
                        o();
                    break
                }
                if ("case" === h || "default" === h) {
                    ":" === v ?
                        function () {
                            y.length && y[y.length - 1] === T && y.pop()
                        }() : (r(), a(), d()),
                        s(),
                        N = !0;
                    break
                }
                K = "NONE",
                    "TK_END_BLOCK" === g ? f(h.toLowerCase(), ["else", "catch", "finally"]) ? (K = "SPACE", o()) : K = "NEWLINE" : "TK_END_COMMAND" !== g || "BLOCK" !== b && "DO_BLOCK" !== b ? "TK_END_COMMAND" === g && "EXPRESSION" === b ? K = "SPACE" : "TK_WORD" === g ? K = "SPACE" : "TK_START_BLOCK" === g ? K = "NEWLINE" : "TK_END_EXPR" === g && (o(), K = "NEWLINE") : K = "NEWLINE",
                    "TK_END_BLOCK" !== g && f(h.toLowerCase(), ["else", "catch", "finally"]) ? a() : f(h, C) || "NEWLINE" === K ? "else" === v ? o() : ("TK_START_EXPR" !== g && "=" !== v || "function" !== h) && ("TK_WORD" !== g || "return" !== v && "throw" !== v ? "TK_END_EXPR" !== g ? "TK_START_EXPR" === g && "var" === h || ":" === v || ("if" === h && "TK_WORD" === g && "else" === x ? o() : a()) : f(h, C) && ")" !== v && a() : o()) : "SPACE" === K && o(),
                    s(),
                    x = h,
                "var" === h && (z = !0, L = !1);
                break;
            case "TK_END_COMMAND":
                s(),
                    z = !1;
                break;
            case "TK_STRING":
                "TK_START_BLOCK" === g || "TK_END_BLOCK" === g ? a() : "TK_WORD" === g && o(),
                    s();
                break;
            case "TK_OPERATOR":
                var R = !0,
                    P = !0;
                if (z && "," !== h && (L = !0, ":" === h && (z = !1)), ":" === h && N) {
                    s(),
                        a();
                    break
                }
                if (N = !1, "," === h) {
                    z ? L ? (s(), a(), L = !1) : (s(), o()) : "TK_END_BLOCK" === g ? (s(), a()) : "BLOCK" === b ? (s(), a()) : (s(), o());
                    break
                }
                "--" === h || "++" === h ? ";" === v ? (R = !0, P = !1) : (R = !1, P = !1) : "!" === h && "TK_START_EXPR" === g ? (R = !1, P = !1) : "TK_OPERATOR" === g ? (R = !1, P = !1) : "TK_END_EXPR" === g ? (R = !0, P = !0) : "." === h ? (R = !1, P = !1) : ":" === h && (R = !!v.match(/^\d+$/)),
                R && o(),
                    s(),
                P && o();
                break;
            case "TK_BLOCK_COMMENT":
                a(),
                    s(),
                    a();
                break;
            case "TK_COMMENT":
                o(),
                    s(),
                    a();
                break;
            case "TK_UNKNOWN":
                s()
        }
        g = O,
            v = h
    }
    return y.join("")
}

layui.define(["layer", "form"], function (t) {
    var e = function (t) {
            var e = t.originalEvent.changedTouches[0];
            return document.elementFromPoint(e.clientX, e.clientY)
        },
        i = layui.$,
        n = layui.layer,
        l = layui.form,
        a = (layui.hint(), layui.device()),
        o = "layui-disabled",
        s = function () {
            var t = this;
            t.index = 0,t.config = {
                    tool: ["strong", "italic", "underline", "del", "|", "left", "center", "right", "|", "link", "unlink", "face", "image"],
                    uploadImage: {
                        url: "",
                        headers: {},
                        data: {},
                        field: "file",
                        accept: "image",
                        acceptMime: "image/*",
                        exts: "jpg|png|gif|bmp|jpeg",
                        size: 10240,
                        done: function (t) {
                        }
                    },
                    uploadVideo: {
                        url: "",
                        headers: {},
                        data: {},
                        field: "file",
                        accept: "video",
                        acceptMime: "video/*",
                        exts: "mp4|flv|avi|rm|rmvb",
                        size: 20480,
                        done: function (t) {
                        }
                    },
                    uploadFiles: {
                        url: "",
                        headers: {},
                        data: {},
                        field: "file",
                        accept: "file",
                        acceptMime: "file/*",
                        exts: "",
                        size: 20480,
                        done: function (t) {
                        }
                    },
                    calldel: {
                        url: "",
                        headers: {},
                        data: {},
                        done: function (t) {
                        }
                    },
                    quote: {
                        style: [],
                        js: []
                    },
                    customTheme: {
                        video: {
                            title: [],
                            content: [],
                            preview: []
                        }
                    },
                    customlink: {
                        title: "自定义链接",
                        href: "",
                        onmouseup: ""
                    },
                    facePath: layui.cache.dir,
                    devmode: !1,
                    hideTool: [],
                    height: 280
                }
        };
    s.prototype.set = function (t) {
        var e = this;
        return i.extend(!0, e.config, t),e
    },
        s.prototype.on = function (t, e) {
            return layui.onevent("layedit", t, e)
        },
        s.prototype.build = function (t, e) {
            e = e || {};
            var n = this,
                l = n.config,
                o = "layui-layedit",
                s = i("string" == typeof t ? "#" + t : t),
                c = "LAY_layedit_" + ++n.index,
                u = s.next("." + o),
                f = i.extend({}, l, e),
                p = function () {
                    var t = [],
                        e = {};
                    return f._elem = s,
                        layui.each(f.hideTool, function (t, i) {
                            e[i] = !0
                        }),
                        layui.each(f.tool, function (i, n) {
                            C[n] && !e[n] && t.push(C[n])
                        }),
                        t.join("")
                }(),
                m = i(['<div class="' + o + '">', '<div class="layui-unselect layui-layedit-tool">' + p.replace("layBkColor_Index", "layBkColor_" + n.index).replace("layFontColor_Index", "layFontColor_" + n.index) + "</div>", '<div class="layui-layedit-iframe">', '<iframe id="' + c + '" name="' + c + '" textarea="' + t + '" frameborder="0"></iframe>', "</div>", "</div>"].join(""));
            return a.ie && a.ie < 8 ? s.removeClass("layui-hide").addClass("layui-show") : (u[0] && u.remove(), d.call(n, m, s[0], f), s.addClass("layui-hide").after(m), layui.use(["colorpicker", "jquery"], function () {
                for (var t = layui.colorpicker, e = (layui.jquery, 0); e <= n.index; e++) t.render({
                    elem: "#layBkColor_" + e,
                    predefine: !0,
                    colors: ["#800000", "#cc0000", "#999999", "#ff8c00", "#ffb800", "#ff7800", "#1e90ff", "#009688", "#5fb878", "#ffffff", "#000000"],
                    size: "xs",
                    done: function (t) {
                        var e = r(this.elem.split("_")[1]);
                        a.ie ? e[0].document.execCommand("backColor", !1, t) : e[0].document.execCommand("hiliteColor", !1, t),
                            setTimeout(function () {
                                e[0].document.body.focus()
                            }, 10)
                    }
                }),
                    t.render({
                        elem: "#layFontColor_" + e,
                        predefine: !0,
                        colors: ["#800000", "#cc0000", "#999999", "#ff8c00", "#ffb800", "#ff7800", "#1e90ff", "#009688", "#5fb878", "#ffffff", "#000000"],
                        size: "xs",
                        color: "#000",
                        done: function (t) {
                            var e = r(this.elem.split("_")[1]);
                            e[0].document.execCommand("forecolor", !1, t),
                                setTimeout(function () {
                                    e[0].document.body.focus()
                                }, 10)
                        }
                    })
            }), n.index)
        },
        s.prototype.getContent = function (t) {
            var e = r(t);
            if (e[0]) return c(e[0].document.body.innerHTML)
        },
        s.prototype.getText = function (t) {
            var e = r(t);
            if (e[0]) return i(e[0].document.body).text()
        },
        s.prototype.setContent = function (t, e, n) {
            var l = r(t);
            l[0] && (n ? i(l[0].document.body).append(e) : i(l[0].document.body).html(e), this.sync(t))
        },
        s.prototype.sync = function (t) {
            var e = r(t);
            if (e[0]) {
                i("#" + e[1].attr("textarea")).val(c(e[0].document.body.innerHTML))
            }
        },
        s.prototype.getSelection = function (t) {
            var e = r(t);
            if (e[0]) {
                var i = p(e[0].document);
                return document.selection ? i.text : i.toString()
            }
        };
    var d = function (t, e, n) {
            var l = this,
                a = t.find("iframe");
            a.css({
                height: n.height
            }).on("load", function () {
                var o = a.contents(),
                    s = a.prop("contentWindow"),
                    d = o.find("head"),
                    r = i(["<style>", "*{margin: 0; padding: 0;}", "body{padding: 10px; line-height: 20px; overflow-x: hidden; word-wrap: break-word; font: 14px Helvetica Neue,Helvetica,PingFang SC,Microsoft YaHei,Tahoma,Arial,sans-serif; -webkit-box-sizing: border-box !important; -moz-box-sizing: border-box !important; box-sizing: border-box !important;}", "a{color:#01AAED; text-decoration:none;}a:hover{color:#c00}", "p{margin-bottom: 10px;}", "video{max-width:400px;}", "td{border: 1px solid #DDD;width:80px}", "table{border-collapse: collapse;}", 'a[name]:before{content:"§";color: #01aaed;font-weight: bold;}', "img{display: inline-block; border: none; vertical-align: middle;}", "pre{margin: 10px 0; padding: 10px; line-height: 20px; border: 1px solid #ddd; border-left-width: 6px; background-color: #F2F2F2; color: #333; font-family: Courier New; font-size: 12px;}", "</style>"].join("")),
                    c = o.find("body"),
                    f = function () {
                        var t = [];
                        return layui.each(n.quote.style, function (e, i) {
                            t.push('<link href="' + i + '" rel="stylesheet"/>')
                        }),
                            layui.each(n.quote.js, function (e, i) {
                                t.push('<script src="' + i + '"><\/script>')
                            }),
                            t.join("")
                    }();
                d.append(r),
                    d.append(f),
                    c.attr("contenteditable", "true").css({
                        "min-height": n.height
                    }).html(e.value || ""),
                    u.apply(l, [s, a, e, n]),
                    g.call(l, s, t, n)
            })
        },
        r = function (t) {
            var e = i("#LAY_layedit_" + t);
            return [e.prop("contentWindow"), e]
        },
        c = function (t) {
            return 8 == a.ie && (t = t.replace(/<.+>/g, function (t) {
                return t.toLowerCase()
            })),
                t
        },
        u = function (t, e, l, o) {
            var s = t.document,
                d = i(s.body);
            d.on("keydown", function (t) {
                if (13 === t.keyCode) {
                    var e = p(s),
                        i = m(e),
                        l = i.parentNode;
                    if ("pre" === l.tagName.toLowerCase()) {
                        if (t.shiftKey) return;
                        return n.msg("请暂时用shift+enter"),
                            !1
                    }
                    "body" === l.tagName.toLowerCase() && s.execCommand("formatBlock", !1, "<p>"),
                        setTimeout(function () {
                            s.execCommand("formatBlock", !1, "<p>")
                        }, 10)
                }
            }),
                i(l).parents("form").on("submit", function () {
                    var t = d.html();
                    8 == a.ie && (t = t.replace(/<.+>/g, function (t) {
                        return t.toLowerCase()
                    })),
                        l.value = t
                }),
                d.on("paste", function (e) {
                    s.execCommand("formatBlock", !1, "<p>"),
                        setTimeout(function () {
                            f.call(t, d),
                                l.value = d.html()
                        }, 100)
                })
        },
        f = function (t) {
            var e = this;
            e.document;
            t.find("*[style]").each(function () {
                var t = this.style.textAlign;
                this.removeAttribute("style"),
                    i(this).css({
                        "text-align": t || ""
                    })
            }),
                t.find("script,link").remove()
        },
        p = function (t) {
            return t.selection ? t.selection.createRange() : t.getSelection().getRangeAt(0)
        },
        m = function (t) {
            return t.endContainer || t.parentElement().childNodes[0]
        },
        y = function (t, e, i) {
            var l = this.document,
                o = document.createElement(t),
                s = document.createElement("p");
            for (var d in e) o.setAttribute(d, e[d]);
            if (o.removeAttribute("text"), a.ie) {
                var r = i.text || e.text;
                if ("a" === t && !r) return;
                r && (o.innerHTML = r),
                    n.msg("暂不支持IE浏览器"),
                    i.selectNode(this.document.body.childNodes.item(0)),
                    i.insertNode(o)
            } else {
                var r = i.toString() || e.text;
                if ("a" === t && !r) return;
                r && (o.innerHTML = r);
                var c = m(i),
                    u = c.parentNode;
                "p" != t && "div" != t && "P" != u.tagName && "<br>" != c.innerHTML ? s.appendChild(o) : s = o,
                "<br>" != c.innerHTML && "div" != t || (i.selectNode(c), i.deleteContents()),
                    i.deleteContents(),
                    i.insertNode(s),
                "img" == t && "<br>" == c.innerHTML && (l.execCommand("formatBlock", !1, "<p>"), l.execCommand("justifyCenter"), setTimeout(function () {
                    body.focus()
                }, 10))
            }
        },
        h = function (t, e) {
            var n = this.document,
                l = "layedit-tool-active",
                a = m(p(n)),
                s = function (e) {
                    return t.find(".layedit-tool-" + e)
                };
            e && e[e.hasClass(l) ? "removeClass" : "addClass"](l),
                t.find(">i").removeClass(l),
                s("unlink").addClass(o),
                i(a).parents().each(function () {
                    var t = this.tagName.toLowerCase(),
                        e = this.style.textAlign;
                    "p" === t && ("center" === e ? s("center").addClass(l) : "right" === e ? s("right").addClass(l) : s("left").addClass(l)),
                    "a" === t && (s("link").addClass(l), s("unlink").removeClass(o))
                })
        },
        g = function (t, e, l) {
            var a = t.document,
                s = i(a.body),
                d = {
                    link: function (e) {
                        var n = m(e),
                            a = i(n).parent();
                        v.call(s, {
                            href: a.attr("href"),
                            target: a.attr("target"),
                            rel: a.attr("rel"),
                            text: a.attr("text") || e.toString(),
                            dmode: l.devmode
                        }, function (i) {
                            var n = a[0];
                            if ("A" === n.tagName) n.href = i.url,
                                n.rel = i.rel,
                            "" == i.rel && n.removeAttr("rel"),
                                n.target = i.target,
                            "_self" != i.target && void 0 != i.target || n.removeAttr("target"),
                            "" != i.text && (n.text = i.text);
                            else {
                                var l = {
                                    target: i.target,
                                    href: i.url,
                                    rel: i.rel,
                                    text: i.text
                                };
                                "" != i.rel && void 0 != i.rel || delete l.rel,
                                "_self" != i.target && void 0 != i.target || delete l.target,
                                "" == i.text && (l.text = l.href),
                                    y.call(t, "a", l, e)
                            }
                        })
                    },
                    unlink: function (t) {
                        a.execCommand("unlink")
                    },
                    face: function (e) {
                        T.call(this, {
                            facePath: l.facePath
                        }, function (i) {
                            y.call(t, "img", {
                                src: i.src,
                                alt: i.alt
                            }, e),
                                setTimeout(function () {
                                    s.focus()
                                }, 10)
                        })
                    },
                    image: function (e) {
                        var a = this;
                        layui.use("upload", function (o) {
                            var d = l.uploadImage || {};
                            o.render({
                                url: d.url,
                                headers: d.headers,
                                data: d.data,
                                field: d.field,
                                accept: d.accept,
                                acceptMime: d.acceptMime,
                                exts: d.exts,
                                size: d.size,
                                elem: i(a).find("input")[0],
                                done: function (i) {
                                    0 == i.code ? (i.data = i.data || {}, y.call(t, "img", {
                                        src: i.data.src,
                                        alt: i.data.title
                                    }, e), d.done(i), setTimeout(function () {
                                        s.focus()
                                    }, 10)) : n.msg(i.msg || "上传失败")
                                }
                            })
                        })
                    },
                    code: function (e) {
                        var i = l.codeConfig || {
                            hide: !1
                        };
                        A.call(s, {
                            hide: i.hide,
                            default:
                            i.default
                        }, function (i) {
                            y.call(t, "pre", {
                                text: '<code class="'+i.lang+'">'+i.code+'</code>',
                                "lay-lang": i.lang
                            }, e),
                                setTimeout(function () {
                                    s.focus()
                                }, 10)
                        })
                    },
                    images: function (e) {
                        n.open({
                            type: 1,
                            id: "fly-jie-image-upload",
                            title: "图片管理",
                            shade: .05,
                            shadeClose: !0,
                            area: function () {
                                return /mobile/i.test(navigator.userAgent) || i(window).width() <= 485 ? ["90%"] : ["485px"]
                            }(),
                            offset: function () {
                                return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                            }(),
                            skin: "layui-layer-border",
                            content: ['<ul class="layui-form layui-form-pane" style="margin: 20px 20px 0 20px;">', '<li class="layui-form-item">', '<div class="layui-upload">', '<button type="button" class="layui-btn" id="LayEdit_InsertImages"><i class="layui-icon"></i>多图上传</button> ', '<blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;min-height: 116px">', '  预览图(点击图片可删除)：<div class="layui-upload-list" id="imgsPrev"></div>', "</blockquote>", "</div>", "</li>", '<li class="layui-form-item" style="position: relative;width: 48%;display: inline-block;">', '<label class="layui-form-label" style="position: relative;z-index: 10;width: 60px;">宽度</label>', '<input type="text" required name="imgWidth" placeholder="px" style="position: absolute;width: 100%;padding-left: 70px;left: 0;top:0" value="" class="layui-input">', "</li>", '<li class="layui-form-item" style="position: relative;width: 48%;display: inline-block;margin-left: 4%;">', '<label class="layui-form-label" style="width: 60px;position: relative;z-index: 10;">高度</label>', '<input type="text" required name="imgHeight" placeholder="px" style="position: absolute;width: 100%;padding-left: 70px;left: 0;top:0" value="" class="layui-input">', "</li>", "</ul>"].join(""),
                            btn: ["确定", "取消"],
                            btnAlign: "c",
                            yes: function (i, l) {
                                var a = "";
                                "" != l.find('input[name="imgWidth"]').val() && (a += "width:" + l.find('input[name="imgWidth"]').val() + "px;"),
                                "" != l.find('input[name="imgHeight"]').val() && (a += "height:" + l.find('input[name="imgHeight"]').val() + "px;"),
                                    0 === l.find("#imgsPrev").find("img").length ? n.msg("请选择要插入的图片") : (y.call(t, "p", {
                                        text: l.find("#imgsPrev").html().replace(new RegExp(/(max-width:70px;margin:2px)/g), a)
                                    }, e), n.close(i))
                            },
                            btn2: function (t, e) {
                                if (e.find("#imgsPrev img").length > 0) {
                                    for (var n = "", a = 0; a < e.find("#imgsPrev img").length; a++) n += e.find("#imgsPrev img")[a].src;
                                    var o = l.calldel;
                                    "" != o.url && i.post(o.url, {
                                        imgpath: n
                                    }, function (t) {
                                        o.done(t)
                                    })
                                }
                            },
                            // cancel: function () {
                            //     if (layero.find("#imgsPrev img").length > 0) {
                            //         for (var t = "", e = 0; e < layero.find("#imgsPrev img").length; e++) t += layero.find("#imgsPrev img")[e].src;
                            //         var n = l.calldel;
                            //         "" != n.url && i.post(n.url, {
                            //             imgpath: t
                            //         }, function (t) {
                            //             n.done(t)
                            //         })
                            //     }
                            // },
                            success: function (t, e) {
                                layui.use("upload", function () {
                                    var e = layui.upload,
                                        a = l.uploadImage || {},
                                        o = [];
                                    e.render({
                                        elem: "#LayEdit_InsertImages",
                                        url: a.url,
                                        field: a.field,
                                        headers: a.headers,
                                        method: a.type,
                                        accept: a.accept,
                                        acceptMime: a.acceptMime,
                                        exts: a.exts,
                                        size: a.size,
                                        multiple: !0,
                                        before: function (t) {
                                            t.preview(function (t, e, n) {
                                                -1 === o.indexOf(t) && i("#imgsPrev").append('<img data-index="' + t + '" src="' + n + '" alt="' + e.name + '" style="max-width:70px;margin:2px" class="layui-upload-img"/> ')
                                            })
                                        },
                                        allDone: function () {
                                            for (var t = 0; t < o.length; t++) i("#imgsPrev").find('img[data-index="' + o[t] + '"]').remove()
                                        },
                                        error: function (t, e) {
                                            o.push(t)
                                        },
                                        done: function (e, o, s) {
                                            0 == e.code ? (e.data = e.data || {}, i("#imgsPrev img:last")[0].src = e.data.src, a.done(e)) : 2 == e.code ? (e.data = e.data || {}, i("#imgsPrev img:last")[0].src = e.data.src, a.done(e)) : n.msg(e.msg || "上传失败"),
                                                t.find(".layui-upload-img").on("click", function () {
                                                    var t = this.src,
                                                        e = this.getAttribute("data-index");
                                                    n.confirm("是否删除该图片?", {
                                                        icon: 3,
                                                        title: "提示"
                                                    }, function (a) {
                                                        var o = l.calldel;
                                                        "" != o.url ? i.post(o.url, {
                                                            imgpath: t
                                                        }, function (t) {
                                                            i("#imgsPrev img[data-index=" + e + "]")[0].remove(),
                                                                o.done(t)
                                                        }) : (n.msg("没有配置回调参数"), i("#imgsPrev img[data-index=" + e + "]")[0].remove()),
                                                            n.close(a)
                                                    })
                                                })
                                        }
                                    })
                                })
                            }
                        })
                    },
                    image_alt: function (e) {
                        n.open({
                            type: 1,
                            id: "fly-jie-image-upload",
                            title: "图片管理",
                            shade: .05,
                            shadeClose: !0,
                            area: function () {
                                return /mobile/i.test(navigator.userAgent) || i(window).width() <= 485 ? ["90%"] : ["485px"]
                            }(),
                            offset: function () {
                                return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                            }(),
                            skin: "layui-layer-border",
                            content: ['<ul class="layui-form layui-form-pane" style="margin: 20px 20px 0 20px">', '<li class="layui-form-item" style="position: relative">', '<button type="button" class="layui-btn" id="LayEdit_InsertImage" style="width: 110px;position: relative;z-index: 10;"><i class="layui-icon"></i>上传图片</button>', '<input type="text" name="Imgsrc" placeholder="请选择文件" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" class="layui-input">', "</li>", '<li class="layui-form-item" style="position: relative">', '<label class="layui-form-label" style="width: 110px;position: relative;z-index: 10;">描述</label>', '<input type="text" required name="altStr" placeholder="alt属性" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" value="" class="layui-input">', "</li>", '<li class="layui-form-item" style="position: relative">', '<label class="layui-form-label" style="width: 110px;position: relative;z-index: 10;">宽度</label>', '<input type="text" required name="imgWidth" placeholder="px" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" value="" class="layui-input">', "</li>", '<li class="layui-form-item" style="position: relative">', '<label class="layui-form-label" style="width: 110px;position: relative;z-index: 10;">高度</label>', '<input type="text" required name="imgHeight" placeholder="px" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" value="" class="layui-input">', "</li>", "</ul>"].join(""),
                            btn: ["确定", "取消"],
                            btnAlign: "c",
                            yes: function (i, l) {
                                var a = "",
                                    o = l.find('input[name="altStr"]'),
                                    s = l.find('input[name="Imgsrc"]');
                                "" != l.find('input[name="imgWidth"]').val() && (a += "width:" + l.find('input[name="imgWidth"]').val() + "px;"),
                                "" != l.find('input[name="imgHeight"]').val() && (a += "height:" + l.find('input[name="imgHeight"]').val() + "px;"),
                                    "" == s.val() ? n.msg("请选择一张图片或输入图片地址") : (y.call(t, "img", {
                                        src: s.val(),
                                        alt: o.val(),
                                        style: a
                                    }, e), n.close(i))
                            },
                            btn2: function (t, e) {
                                var n = l.calldel;
                                "" != n.url && i.post(n.url, {
                                    imgpath: e.find('input[name="Imgsrc"]').val()
                                }, function (t) {
                                    n.done(t)
                                })
                            },
                            // cancel: function () {
                            //     var t = l.calldel;
                            //     "" != t.url && i.post(t.url, {
                            //         imgpath: layero.find('input[name="Imgsrc"]').val()
                            //     }, function (e) {
                            //         t.done(e)
                            //     })
                            // },
                            success: function (t, e) {
                                layui.use("upload", function (e) {
                                    var i, e = layui.upload,
                                        a = t.find('input[name="altStr"]'),
                                        o = t.find('input[name="Imgsrc"]'),
                                        s = l.uploadImage || {};
                                    e.render({
                                        elem: "#LayEdit_InsertImage",
                                        url: s.url,
                                        field: s.field,
                                        headers: s.headers,
                                        accept: s.accept,
                                        acceptMime: s.acceptMime,
                                        exts: s.exts,
                                        size: s.size,
                                        before: function (t) {
                                            i = n.msg("文件上传中,请稍等哦", {
                                                icon: 16,
                                                shade: .3,
                                                time: 0
                                            })
                                        },
                                        done: function (t, e, l) {
                                            if (n.close(i), 0 == t.code) t.data = t.data || {},
                                                o.val(t.data.src),
                                                a.val(t.data.name),
                                                s.done(t);
                                            else if (2 == t.code) var d = n.open({
                                                type: 1,
                                                anim: 2,
                                                icon: 5,
                                                title: "提示",
                                                area: ["390px", "260px"],
                                                offset: "t",
                                                content: t.msg + "<div style='text-align:center;'><img src='" + t.data.src + "' style='max-height:80px'/></div><p style='text-align:center'>确定使用该文件吗？</p>",
                                                btn: ["确定", "取消"],
                                                yes: function () {
                                                    t.data = t.data || {},
                                                        o.val(t.data.src),
                                                        a.val(t.data.name),
                                                        n.close(d)
                                                }
                                            });
                                            else n.msg(t.msg || "上传失败")
                                        }
                                    })
                                })
                            }
                        })
                    },
                    video: function (e) {
                        var a = l.customTheme || {
                                video: []
                            },
                            o = "";
                        a.video.title.length > 0 && (o = AddCustomThemes(a.video.title, a.video.content, a.video.preview)),
                            n.open({
                                type: 1,
                                id: "fly-jie-video-upload",
                                title: "视频管理",
                                shade: .05,
                                shadeClose: !0,
                                area: function () {
                                    return /mobile/i.test(navigator.userAgent) || i(window).width() <= 485 ? ["90%"] : ["485px"]
                                }(),
                                offset: function () {
                                    return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                                }(),
                                skin: "layui-layer-border",
                                content: ['<ul class="layui-form layui-form-pane" style="margin: 20px 20px 0 20px">', '<li class="layui-form-item" style="position: relative">', '<button type="button" class="layui-btn" id="LayEdit_InsertVideo" style="width: 110px;position: relative;z-index: 10;"> <i class="layui-icon"></i>上传视频</button>', '<input type="text" name="video" placeholder="请选择文件" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" class="layui-input">', "</li>", '<li class="layui-form-item" style="position: relative">', '<button type="button" class="layui-btn" id="LayEdit_InsertImage" style="width: 110px;position: relative;z-index: 10;"> <i class="layui-icon"></i>上传封面</button>', '<input type="text" name="cover" placeholder="请选择文件" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" class="layui-input">', "</li>", o, "</ul>"].join(""),
                                btn: ["确定", "取消"],
                                btnAlign: "c",
                                yes: function (i, o) {
                                    var s = o.find('input[name="video"]'),
                                        d = o.find('input[name="cover"]'),
                                        r = o.find('select[name="theme"]');
                                    if ("" == s.val()) n.msg("请选择一个视频或输入视频地址");
                                    else {
                                        var c = '&nbsp;<video src="' + s.val() + '" poster="' + d.val() + '" ' + l.videoAttr + ' controls="controls" >您的浏览器不支持video播放</video>&nbsp;',
                                            u = "";
                                        a.video.title.length > 0 && r.length > 0 && (u = r[0].options[r[0].selectedIndex].value),
                                            y.call(t, "div", {
                                                text: c,
                                                class: u
                                            }, e),
                                            n.close(i)
                                    }
                                },
                                btn2: function (t, e) {
                                    var n = l.calldel;
                                    "" != n.url && i.post(n.url, {
                                        imgpath: e.find('input[name="cover"]').val(),
                                        filepath: e.find('input[name="video"]').val()
                                    }, function (t) {
                                        n.done(t)
                                    })
                                },
                                // cancel: function () {
                                //     var t = l.calldel;
                                //     "" != t.url && i.post(t.url, {
                                //         imgpath: layero.find('input[name="cover"]').val(),
                                //         filepath: layero.find('input[name="video"]').val()
                                //     }, function (e) {
                                //         t.done(e)
                                //     })
                                // },
                                success: function (t, e) {
                                    layui.use("upload", function (e) {
                                        var i, o = t.find('input[name="video"]'),
                                            s = t.find('input[name="cover"]'),
                                            e = layui.upload,
                                            d = l.uploadImage || {},
                                            r = l.uploadVideo || {};
                                        e.render({
                                            elem: "#LayEdit_InsertImage",
                                            url: d.url,
                                            headers: d.headers,
                                            field: d.field,
                                            accept: d.accept,
                                            acceptMime: d.acceptMime,
                                            exts: d.exts,
                                            size: d.size,
                                            before: function (t) {
                                                i = n.msg("文件上传中,请稍等哦", {
                                                    icon: 16,
                                                    shade: .3,
                                                    time: 0
                                                })
                                            },
                                            done: function (t, e, l) {
                                                if (n.close(i), 0 == t.code) t.data = t.data || {},
                                                    s.val(t.data.src),
                                                    d.done(t);
                                                else if (2 == t.code) var a = n.open({
                                                    type: 1,
                                                    anim: 2,
                                                    icon: 5,
                                                    title: "提示",
                                                    area: ["390px", "260px"],
                                                    offset: "t",
                                                    content: t.msg + "<div><img src='" + t.data.src + "' style='max-height:100px'/></div><p style='text-align:center'>确定使用该文件吗？</p>",
                                                    btn: ["确定", "取消"],
                                                    yes: function () {
                                                        t.data = t.data || {},
                                                            s.val(t.data.src),
                                                            n.close(a)
                                                    }
                                                });
                                                else n.msg(t.msg || "上传失败")
                                            }
                                        }),
                                            e.render({
                                                elem: "#LayEdit_InsertVideo",
                                                url: r.url,
                                                field: r.field,
                                                headers: f.headers,
                                                accept: r.accept,
                                                acceptMime: r.acceptMime,
                                                exts: r.exts,
                                                size: r.size,
                                                before: function (t) {
                                                    i = n.msg("文件上传中,请稍等哦", {
                                                        icon: 16,
                                                        shade: .3,
                                                        time: 0
                                                    })
                                                },
                                                done: function (t, e, l) {
                                                    if (n.close(i), 0 == t.code) t.data = t.data || {},
                                                        o.val(t.data.src),
                                                        r.done(t);
                                                    else if (2 == t.code) var a = n.open({
                                                        type: 1,
                                                        anim: 2,
                                                        icon: 5,
                                                        title: "提示",
                                                        area: ["390px", "260px"],
                                                        offset: "t",
                                                        content: t.msg + "<div><video src='" + t.data.src + "' style='max-height:100px' controls='controls'/></div><p style='text-align:center'>确定使用该文件吗？</p>",
                                                        btn: ["确定", "取消"],
                                                        yes: function () {
                                                            t.data = t.data || {},
                                                                o.val(t.data.src),
                                                                n.close(a)
                                                        }
                                                    });
                                                    else n.msg(t.msg || "上传失败")
                                                }
                                            });
                                        var c = t.find('select[name="theme"]');
                                        a.video.title.length > 0 && c.length > 0 && t.find('select[name="theme"]').on("change mouseover", function () {
                                            n.tips("<img src='" + c[0].options[c[0].selectedIndex].attributes["data-img"].value + "' />", this)
                                        })
                                    })
                                }
                            })
                    },
                    attachment: function (e) {
                        n.open({
                            type: 1,
                            id: "fly-jie-image-upload",
                            title: "附件上传",
                            shade: .05,
                            shadeClose: !0,
                            area: function () {
                                return /mobile/i.test(navigator.userAgent) || i(window).width() <= 485 ? ["90%"] : ["485px"]
                            }(),
                            offset: function () {
                                return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                            }(),
                            skin: "layui-layer-border",
                            content: ['<ul class="layui-form layui-form-pane" style="margin: 20px 20px 0 20px;">', '<li class="layui-form-item">', '<div class="layui-upload">', '<button type="button" class="layui-btn" id="LayEdit_InsertFiles"><i class="layui-icon"></i>上传附件</button> ', '<blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;min-height: 116px">', '  上传列表：<div class="layui-upload-list" id="filesPrev"></div>', "</blockquote>", "</div>", "</li>", "</ul>"].join(""),
                            btn: ["确定", "取消"],
                            btnAlign: "c",
                            yes: function (i, l) {
                                0 === l.find("#filesPrev").find("a").length ? n.msg("请选择要上传的附件") : (y.call(t, "p", {
                                    text: l.find("#filesPrev").html()
                                }, e), n.close(i))
                            },
                            success: function (t, e) {
                                layui.use("upload", function () {
                                    var e = layui.upload,
                                        a = l.uploadFiles || {},
                                        o = [];
                                    e.render({
                                        elem: "#LayEdit_InsertFiles",
                                        url: a.url,
                                        headers: a.headers,
                                        field: a.field,
                                        method: a.type,
                                        accept: a.accept,
                                        acceptMime: a.acceptMime,
                                        exts: a.exts,
                                        size: a.size,
                                        multiple: !0,
                                        before: function (t) {
                                            t.preview(function (t, e, n) {
                                                -1 === o.indexOf(t) && i("#filesPrev").append('<a data-index="' + t + '" target="_blank" href="' + n + '" alt="' + e.name + '" >' + e.name + "</a>&nbsp;")
                                            })
                                        },
                                        allDone: function () {
                                            for (var t = 0; t < o.length; t++) i("#filesPrev").find('a[data-index="' + o[t] + '"]').remove()
                                        },
                                        error: function (t, e) {
                                            o.push(t)
                                        },
                                        done: function (e, o, s) {
                                            0 == e.code ? (e.data = e.data || {}, i("#filesPrev a:last")[0].href = e.data.src, a.done(e)) : 2 == e.code ? (n.msg(e.msg || "上传失败"), e.data = e.data || {}, i("#filesPrev a:last")[0].href = e.data.src) : n.msg(e.msg || "上传失败"),
                                                t.find(".layui-upload-img").on("click", function () {
                                                    n.confirm("是否删除该附件?", {
                                                        icon: 3,
                                                        title: "提示"
                                                    }, function (t) {
                                                        var e = l.calldel;
                                                        "" != e.url ? i.post(e.url, {
                                                            imgpath: this.src
                                                        }, function (t) {
                                                            i("#filesPrev a:last")[0].remove(),
                                                                e.done(t)
                                                        }) : (n.msg("没有配置回调参数"), i("#filesPrev a:last")[0].remove()),
                                                            n.close(t)
                                                    })
                                                })
                                        }
                                    })
                                })
                            }
                        })
                    },
                    html: function (e) {
                        var n = this,
                            a = n.parentElement.nextElementSibling.firstElementChild.contentDocument.body.innerHTML;
                        if (a = style_html(a, 4, " ", 80), -1 == n.parentElement.nextElementSibling.lastElementChild.id.indexOf("aceHtmleditor")) {
                            n.parentElement.nextElementSibling.setAttribute("style", "z-index: 999; overflow: hidden;height:" + l.height),
                            null !== this.parentElement.parentElement.getAttribute("style") && n.parentElement.nextElementSibling.setAttribute("style", "z-index: 999; overflow: hidden;height:100%"),
                                n.parentElement.nextElementSibling.firstElementChild.style = "position: absolute;left: -32768px;top: -32768px;";
                            var o = document.createElement("div");
                            o.setAttribute("id", n.parentElement.nextElementSibling.firstElementChild.id + "aceHtmleditor"),
                                o.setAttribute("style", "left: 0px;top: 0px;width: 100%;height: 100%"),
                                n.parentElement.nextElementSibling.appendChild(o);
                            var s = ace.edit(n.parentElement.nextElementSibling.firstElementChild.id + "aceHtmleditor");
                            s.setFontSize(14),
                                s.session.setMode("ace/mode/html"),
                                s.setTheme("ace/theme/tomorrow"),
                                s.setValue(a),
                                s.setOption("wrap", "free"),
                                s.gotoLine(0),
                                i(n).siblings("i").addClass("layui-disabled"),
                                i(n).siblings(".layedit-tool-fullScreen").removeClass("layui-disabled"),
                                i(n).removeClass("layui-disabled")
                        } else {
                            var s = ace.edit(n.parentElement.nextElementSibling.firstElementChild.id + "aceHtmleditor");
                            t.document.body.innerHTML = s.getValue(),
                                n.parentElement.nextElementSibling.removeAttribute("style"),
                                this.parentElement.nextElementSibling.firstElementChild.style = "height:" + l.height,
                                this.parentElement.nextElementSibling.lastElementChild.remove(),
                                i(n).siblings("i").removeClass("layui-disabled")
                        }
                    },
                    fullScreen: function (t) {
                        null == this.parentElement.parentElement.getAttribute("style") ? (this.parentElement.parentElement.setAttribute("style", "position: fixed;top: 0;left: 0;height: 100%;width: 100%;background-color: antiquewhite;z-index: 9999;"), this.parentElement.nextElementSibling.style = "height:100%", this.parentElement.nextElementSibling.firstElementChild.style = "height:100%", this.parentElement.nextElementSibling.lastElementChild.id.indexOf("aceHtmleditor") > -1 && (this.parentElement.nextElementSibling.firstElementChild.style = "position: absolute;left: -32768px;top: -32768px;", this.parentElement.nextElementSibling.setAttribute("style", "z-index: 999; overflow: hidden;height:100%"))) : (this.parentElement.parentElement.removeAttribute("style"), this.parentElement.nextElementSibling.removeAttribute("style"), this.parentElement.nextElementSibling.firstElementChild.style = "height:" + l.height, this.parentElement.nextElementSibling.lastElementChild.id.indexOf("aceHtmleditor") > -1 && (this.parentElement.nextElementSibling.firstElementChild.style = "position: absolute;left: -32768px;top: -32768px;", this.parentElement.nextElementSibling.setAttribute("style", "z-index: 999; overflow: hidden;height:" + l.height)))
                    },
                    fontFomatt: function (t) {
                        var e = l.fontFomatt || {
                                code: ["p", "h1", "h2", "h3", "h4", "div"],
                                text: ["正文(p)", "一级标题(h1)", "二级标题(h2)", "三级标题(h3)", "四级标题(h4)", "块级元素(div)"]
                            },
                            i = {},
                            n = {},
                            o = e.code,
                            d = e.text,
                            r = function () {
                                return layui.each(o, function (t, e) {
                                    i[t] = e
                                }),
                                    i
                            }(),
                            c = function () {
                                return layui.each(d, function (t, e) {
                                    n[t] = e
                                }),
                                    n
                            }();
                        E.call(this, {
                            fonts: r,
                            texts: c
                        }, function (t) {
                            a.execCommand("formatBlock", !1, "<" + t + ">"),
                                setTimeout(function () {
                                    s.focus()
                                }, 10)
                        })
                    },
                    fontfamily: function (e) {
                        var i = l.fontfamily || {
                                code: ["font-family:宋体,SimSun", "font-family:微软雅黑,Microsoft YaHei", "font-family:黑体, SimHei", "font-family:楷体,楷体_GB2312, SimKai", "font-family:arial, helvetica,sans-serif", "font-family:arial black,avant garde", "font-family:times new roman"],
                                text: ["宋体", "微软雅黑", "黑体", "楷体", "arial", "arial black", "times new roman"]
                            },
                            n = {},
                            a = {},
                            o = i.code,
                            d = i.text,
                            r = function () {
                                return layui.each(o, function (t, e) {
                                    n[t] = e
                                }),
                                    n
                            }(),
                            c = function () {
                                return layui.each(d, function (t, e) {
                                    a[t] = e
                                }),
                                    a
                            }();
                        k.call(this, {
                            fonts: r,
                            texts: c
                        }, function (i) {
                            y.call(t, "span", {
                                style: i,
                                text: "&nbsp;"
                            }, e),
                                setTimeout(function () {
                                    s.focus()
                                }, 10)
                        })
                    },
                    fontSize: function (e) {
                        var i = l.fontSize || {
                                code: ["font-size:10px", "font-size:12px", "font-size:14px", "font-size:16px", "font-size:18px", "font-size:20px", "font-size:24px", "font-size:26px", "font-size:28px", "font-size:30px", "font-size:32px"],
                                text: ["10px", "12px", "14px", "16px", "18px", "20px", "24px", "26px", "28px", "30px", "32px"]
                            },
                            n = {},
                            a = {},
                            o = i.code,
                            d = i.text,
                            r = function () {
                                return layui.each(o, function (t, e) {
                                    n[t] = e
                                }),
                                    n
                            }(),
                            c = function () {
                                return layui.each(d, function (t, e) {
                                    a[t] = e
                                }),
                                    a
                            }();
                        w.call(this, {
                            fonts: r,
                            texts: c
                        }, function (i) {
                            y.call(t, "span", {
                                style: i,
                                text: "&nbsp;"
                            }, e),
                                setTimeout(function () {
                                    s.focus()
                                }, 10)
                        })
                    },
                    customlink: function (e) {
                        var n = m(e),
                            a = i(n).parent();
                        x.call(s, {
                            title: l.customlink.title
                        }, function (i) {
                            var n = a[0];
                            "A" === n.tagName ? (n.href = i.url, n.rel = i.rel) : y.call(t, "a", {
                                target: "_blank",
                                href: l.customlink.href,
                                rel: "nofollow",
                                text: i.text,
                                onmouseup: l.customlink.onmouseup
                            }, e)
                        })
                    },
                    anchors: function (e) {
                        b.call(s, {}, function (i) {
                            y.call(t, "a", {
                                name: i.text,
                                text: " "
                            }, e)
                        })
                    },
                    table: function (e) {
                        _.call(this, function (i) {
                            for (var n = "<tr>", l = 0; l < i.cells; l++) n += "<td></td>";
                            n += "</tr>";
                            for (var a = n, l = 0; l < i.rows; l++) n += a;
                            y.call(t, "table", {
                                text: n
                            }, e),
                                setTimeout(function () {
                                    s.focus()
                                }, 10)
                        })
                    },
                    addhr: function (e) {
                        y.call(t, "hr", {}, e)
                    },
                    help: function () {
                        n.open({
                            type: 2,
                            title: "帮助",
                            area: ["600px", "380px"],
                            shadeClose: !0,
                            shade: .1,
                            offset: "100px",
                            skin: "layui-layer-msg",
                            content: ["http://www.layui.com/about/layedit/help.html", "no"]
                        })
                    }
                },
                r = e.find(".layui-layedit-tool"),
                c = function () {
                    var e = i(this),
                        n = e.attr("layedit-event"),
                        l = e.attr("lay-command");
                    if (!e.hasClass(o)) {
                        s.focus();
                        var c = p(a),
                            u = c.commonAncestorContainer;
                        l ? (/justifyLeft|justifyCenter|justifyRight/.test(l) && "BODY" === u.parentNode.tagName && a.execCommand("formatBlock", !1, "<p>"), a.execCommand(l), setTimeout(function () {
                            s.focus()
                        }, 10)) : d[n] && d[n].call(this, c, a),
                            h.call(t, r, e)
                    }
                },
                u = /image/;
            r.find(">i").on("mousedown", function () {
                var t = i(this),
                    e = t.attr("layedit-event");
                u.test(e) || c.call(this)
            }).on("click", function () {
                var t = i(this),
                    e = t.attr("layedit-event");
                u.test(e) && c.call(this)
            }),
                s.on("click", function () {
                    h.call(t, r),
                        n.close(T.index),
                        n.close(_.index),
                        n.close(E.index)
                });
            var f = null,
                g = function (e) {
                    if (null != e) {
                        n.close(f);
                        var a, o;
                        switch (a = e.target, o = a.parentNode, a.tagName) {
                            case "IMG":
                                f = n.open({
                                    type: 1,
                                    id: "fly-jie-image-upload",
                                    title: "图片管理",
                                    area: function () {
                                        return /mobile/i.test(navigator.userAgent) || i(window).width() <= 485 ? ["90%"] : ["485px"]
                                    }(),
                                    offset: function () {
                                        return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                                    }(),
                                    shade: 0,
                                    closeBtn: !1,
                                    content: ['<ul class="layui-form layui-form-pane" style="margin: 20px 20px 0 20px">', '<li class="layui-form-item" style="position: relative">', '<button type="button" class="layui-btn" id="LayEdit_UpdateImage" style="width: 110px;position: relative;z-index: 10;"> <i class="layui-icon"></i>上传图片</button>', '<input type="text" name="Imgsrc" placeholder="请选择文件" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" value="' + e.target.src + '" class="layui-input">', "</li>", '<li class="layui-form-item" style="position: relative">', '<label class="layui-form-label" style="width: 110px;position: relative;z-index: 10;">描述</label>', '<input type="text" required name="altStr" placeholder="alt属性" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" value="' + e.target.alt + '" class="layui-input">', "</li>", '<li class="layui-form-item" style="position: relative">', '<label class="layui-form-label" style="width: 110px;position: relative;z-index: 10;">宽度</label>', '<input type="text" required name="imgWidth" placeholder="px" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" value="' + (parseInt(e.target.style.width) || "") + '" class="layui-input">', "</li>", '<li class="layui-form-item" style="position: relative">', '<label class="layui-form-label" style="width: 110px;position: relative;z-index: 10;">高度</label>', '<input type="text" required name="imgHeight" placeholder="px" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" value="' + (parseInt(e.target.style.height) || "") + '" class="layui-input">', "</li>", "</ul>"].join(""),
                                    btn: ["确定", "取消", '<span style="color:red">删除</span>'],
                                    btnAlign: "c",
                                    yes: function (t, i) {
                                        var l = i.find('input[name="Imgsrc"]').val(),
                                            a = i.find('input[name="imgWidth"]').val(),
                                            o = i.find('input[name="imgHeight"]').val();
                                        "" == l ? n.msg("请选择一张图片或输入图片地址") : (e.target.src = l, e.target.alt = i.find('input[name="altStr"]').val(), e.target.style.width = "" != a ? a + "px" : "", e.target.style.height = "" != o ? o + "px" : "", n.close(t))
                                    },
                                    btn2: function (t, e) {
                                    },
                                    btn3: function (t, a) {
                                        var o = l.calldel;
                                        "" != o.url ? i.post(o.url, {
                                            imgpath: e.target.src
                                        }, function (t) {
                                            e.toElement.remove(),
                                                o.done(t)
                                        }) : e.toElement.remove(),
                                            n.close(t)
                                    },
                                    success: function (t, e) {
                                        var i = l.uploadImage || {};
                                        return layui.use("upload", function (e) {
                                            var l, a = t.find('input[name="altStr"]'),
                                                o = t.find('input[name="Imgsrc"]');
                                            e = layui.upload,
                                                e.render({
                                                    elem: "#LayEdit_UpdateImage",
                                                    url: i.url,
                                                    headers: i.headers,
                                                    field: i.field,
                                                    accept: i.accept,
                                                    acceptMime: i.acceptMime,
                                                    exts: i.exts,
                                                    size: i.size,
                                                    before: function (t) {
                                                        l = n.msg("文件上传中,请稍等哦", {
                                                            icon: 16,
                                                            shade: .3,
                                                            time: 0
                                                        })
                                                    },
                                                    done: function (t, e, i) {
                                                        if (n.close(l), 0 == t.code) t.data = t.data || {},
                                                            o.val(t.data.src),
                                                            a.val(t.data.name);
                                                        else if (2 == t.code) var s = n.open({
                                                            type: 1,
                                                            anim: 2,
                                                            icon: 5,
                                                            title: "提示",
                                                            area: ["390px", "260px"],
                                                            offset: "t",
                                                            content: t.msg + "<div style='text-align:center;'><img src='" + t.data.src + "' style='max-height:80px'/></div><p style='text-align:center'>确定使用该文件吗？</p>",
                                                            btn: ["确定", "取消"],
                                                            yes: function () {
                                                                t.data = t.data || {},
                                                                    o.val(t.data.src),
                                                                    a.val(t.data.name),
                                                                    n.close(s)
                                                            }
                                                        });
                                                        else n.msg(t.msg || "上传失败")
                                                    }
                                                })
                                        }),
                                            !1
                                    }
                                });
                                break;
                            case "VIDEO":
                                var s = l.customTheme || {
                                        video: []
                                    },
                                    d = "";
                                s.video.title.length > 0 && (d = AddCustomThemes(s.video.title, s.video.content, s.video.preview)),
                                    f = n.open({
                                        type: 1,
                                        id: "fly-jie-video-upload",
                                        title: "视频管理",
                                        shade: .05,
                                        shadeClose: !0,
                                        area: function () {
                                            return /mobile/i.test(navigator.userAgent) || i(window).width() <= 485 ? ["90%"] : ["485px"]
                                        }(),
                                        offset: function () {
                                            return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                                        }(),
                                        skin: "layui-layer-border",
                                        content: ['<ul class="layui-form layui-form-pane" style="margin: 20px 20px 0 20px">', '<li class="layui-form-item" style="position: relative">', '<button type="button" class="layui-btn" id="LayEdit_InsertVideo" style="width: 110px;position: relative;z-index: 10;"> <i class="layui-icon"></i>上传视频</button>', '<input type="text" name="video" value="' + e.target.src + '" placeholder="请选择文件" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" class="layui-input">', "</li>", '<li class="layui-form-item" style="position: relative">', '<button type="button" class="layui-btn" id="LayEdit_InsertImage" style="width: 110px;position: relative;z-index: 10;"> <i class="layui-icon"></i>上传封面</button>', '<input type="text" name="cover" value="' + e.target.poster + '" placeholder="请选择文件" style="position: absolute;width: 100%;padding-left: 120px;left: 0;top:0" class="layui-input">', "</li>", d, "</ul>"].join(""),
                                        btn: ["确定", "取消", '<span style="color:red">删除</span>'],
                                        btnAlign: "c",
                                        yes: function (e, i) {
                                            var l = i.find('input[name="video"]'),
                                                a = i.find('input[name="cover"]'),
                                                o = i.find('select[name="theme"]');
                                            if ("" == l.val()) n.msg("请选择一个视频或输入视频地址");
                                            else {
                                                var d = '&nbsp;<video src="' + l.val() + '" poster="' + a.val() + '" controls="controls" >您的浏览器不支持video播放</video>&nbsp;',
                                                    r = "";
                                                s.video.title.length > 0 && o.length > 0 && (r = o[0].options[o[0].selectedIndex].value),
                                                    y.call(t, "div", {
                                                        text: d,
                                                        class: r
                                                    }, range),
                                                    n.close(e)
                                            }
                                        },
                                        btn2: function (t, e) {
                                        },
                                        btn3: function (t, a) {
                                            var s = l.calldel;
                                            "" != s.url ? i.post(s.url, {
                                                filepath: e.target.src,
                                                imgpath: e.target.poster
                                            }, function (t) {
                                                o.remove(),
                                                    s.done(t)
                                            }) : e.toElement.remove(),
                                                n.close(t)
                                        },
                                        success: function (t, e) {
                                            layui.use("upload", function (e) {
                                                var i, a = t.find('input[name="video"]'),
                                                    o = t.find('input[name="cover"]'),
                                                    e = layui.upload,
                                                    d = l.uploadImage || {},
                                                    r = l.uploadVideo || {};
                                                e.render({
                                                    elem: "#LayEdit_InsertImage",
                                                    url: d.url,
                                                    headers: d.headers,
                                                    field: d.field,
                                                    accept: d.accept,
                                                    acceptMime: d.acceptMime,
                                                    exts: d.exts,
                                                    size: d.size,
                                                    before: function (t) {
                                                        i = n.msg("文件上传中,请稍等哦", {
                                                            icon: 16,
                                                            shade: .3,
                                                            time: 0
                                                        })
                                                    },
                                                    done: function (t, e, l) {
                                                        if (n.close(i), 0 == t.code) t.data = t.data || {},
                                                            o.val(t.data.src),
                                                            d.done(t);
                                                        else if (2 == t.code) var a = n.open({
                                                            type: 1,
                                                            anim: 2,
                                                            icon: 5,
                                                            title: "提示",
                                                            area: ["390px", "260px"],
                                                            offset: "t",
                                                            content: t.msg + "<div><img src='" + t.data.src + "' style='max-height:100px'/></div><p style='text-align:center'>确定使用该文件吗？</p>",
                                                            btn: ["确定", "取消"],
                                                            yes: function () {
                                                                t.data = t.data || {},
                                                                    o.val(t.data.src),
                                                                    n.close(a)
                                                            }
                                                        });
                                                        else n.msg(t.msg || "上传失败")
                                                    }
                                                }),
                                                    e.render({
                                                        elem: "#LayEdit_InsertVideo",
                                                        url: r.url,
                                                        field: r.field,
                                                        headers: r.headers,
                                                        accept: r.accept,
                                                        acceptMime: r.acceptMime,
                                                        exts: r.exts,
                                                        size: r.size,
                                                        before: function (t) {
                                                            i = n.msg("文件上传中,请稍等哦", {
                                                                icon: 16,
                                                                shade: .3,
                                                                time: 0
                                                            })
                                                        },
                                                        done: function (t, e, l) {
                                                            if (n.close(i), 0 == t.code) t.data = t.data || {},
                                                                a.val(t.data.src),
                                                                r.done(t);
                                                            else if (2 == t.code) var o = n.open({
                                                                type: 1,
                                                                anim: 2,
                                                                icon: 5,
                                                                title: "提示",
                                                                area: ["390px", "260px"],
                                                                offset: "t",
                                                                content: t.msg + "<div><video src='" + t.data.src + "' style='max-height:100px' controls='controls'/></div><p style='text-align:center'>确定使用该文件吗？</p>",
                                                                btn: ["确定", "取消"],
                                                                yes: function () {
                                                                    t.data = t.data || {},
                                                                        a.val(t.data.src),
                                                                        n.close(o)
                                                                }
                                                            });
                                                            else n.msg(t.msg || "上传失败")
                                                        }
                                                    });
                                                var c = t.find('select[name="theme"]');
                                                s.video.title.length > 0 && c.length > 0 && t.find('select[name="theme"]').on("change mouseover", function () {
                                                    n.tips("<img src='" + c[0].options[c[0].selectedIndex].attributes["data-img"].value + "' />", this)
                                                })
                                            })
                                        }
                                    });
                                break;
                            case "TD":
                                f = n.open({
                                    type: 1,
                                    title: !1,
                                    shade: 0,
                                    offset: [e.clientY + "px", e.clientX + "px"],
                                    skin: "layui-box layui-util-face",
                                    content: function () {
                                        return '<ul class="layui-clear" style="width: max-content;">' + [, '<li  style="float: initial;width:100%;" lay-command="addnewtr"> 新增行 </li>', '<li  style="float: initial;width:100%;"  lay-command="deltr"> 删除行 </li>'].join("") + "</ul>"
                                    }(),
                                    success: function (t, e) {
                                        t.find("li").on("click", function () {
                                            var t = i(this),
                                                l = t.attr("lay-command");
                                            if (l) switch (l) {
                                                case "deltr":
                                                    o.remove();
                                                    break;
                                                case "addnewtr":
                                                    for (var a = "<tr>", s = 0; s < o.children.length; s++) a += "<td></td>";
                                                    a += "</tr>",
                                                        i(o).after(a)
                                            }
                                            n.close(e)
                                        })
                                    }
                                });
                                break;
                            default:
                                f = n.open({
                                    type: 1,
                                    title: !1,
                                    closeBtn: !1,
                                    offset: function () {
                                        if (/mobile/i.test(navigator.userAgent)) return "auto";
                                        var t = l._elem.next().find("iframe").get(0);
                                        return [t.offsetTop + e.clientY + o.getBoundingClientRect().y + "px", t.offsetLeft + e.clientX + o.getBoundingClientRect().x + "px"]
                                    }(),
                                    shade: function () {
                                        return /mobile/i.test(navigator.userAgent) ? .1 : 0
                                    },
                                    shadeClose: !0,
                                    content: ["<style>", "ul.context-menu > li > a{border: none;border-bottom: 1px solid rgba(0,0,0,.2);border-radius: 0}", "ul.context-menu > li > a:hover{border-color: rgba(0,0,0,.2);background:#eaeaea}", "ul.context-menu > li:last-child > a{border: none;}", "</style>", '<ul style="width:100px" class="context-menu">', '<li><a type="button" class="layui-btn layui-btn-primary layui-btn-sm" style="width:100%" lay-command="left"> 居左 </a></li>', '<li><a type="button" class="layui-btn layui-btn-primary layui-btn-sm" style="width:100%" lay-command="center"> 居中 </a></li>', '<li><a type="button" class="layui-btn layui-btn-primary layui-btn-sm" style="width:100%" lay-command="right"> 居右 </a></li>', '<li><a type="button" class="layui-btn layui-btn-primary layui-btn-sm context-menu-delete" style="width:100%" lay-command="right"> 删除 </a></li>', "</ul>"].join(""),
                                    success: function (t, s) {
                                        var d = l.calldel;
                                        t.find(".layui-btn-primary").on("click", function () {
                                            var t = i(this),
                                                e = t.attr("lay-command");
                                            e && ("VIDEO" == a.tagName ? o.style = "text-align:" + e : a.style = "text-align:" + e),
                                                n.close(s)
                                        }),
                                            t.find(".context-menu-delete").on("click", function () {
                                                "BODY" == a.tagName ? n.msg("不能再删除了") : "VIDEO" == a.tagName ? "" != d.url ? i.post(d.url, {
                                                    filepath: e.target.src,
                                                    imgpath: e.target.poster
                                                }, function (t) {
                                                    o.remove(),
                                                        d.done(t)
                                                }) : o.remove() : "IMG" == a.tagName && "" != d.url ? i.post(d.url, {
                                                    para: e.target.src
                                                }, function (t) {
                                                    a.remove(),
                                                        d.done(t)
                                                }) : a.remove(),
                                                    n.close(s)
                                            })
                                    }
                                })
                        }
                        return !1
                    }
                };
            if (/mobile/i.test(navigator.userAgent)) {
                var C;
                s.on({
                    touchstart: function (t) {
                        C = setTimeout(function () {
                            g(t),
                                clearTimeout(C)
                        }, 300),
                            t.preventDefault()
                    },
                    touchmove: function () {
                        clearTimeout(C)
                    },
                    touchend: function () {
                        clearTimeout(C)
                    }
                })
            } else s.on("contextmenu", function (t) {
                return g(t)
            })
        },
        v = function t(e, a) {
            var o = e.dmode,
                s = this,
                d = n.open({
                    type: 1,
                    id: "LAY_layedit_link",
                    area: function () {
                        return /mobile/i.test(navigator.userAgent) || i(window).width() <= 460 ? ["90%"] : ["460px"]
                    }(),
                    offset: function () {
                        return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                    }(),
                    shade: .05,
                    shadeClose: !0,
                    moveType: 1,
                    title: "超链接",
                    skin: "layui-layer-msg",
                    content: ['<ul class="layui-form" style="margin: 15px;">', '<li class="layui-form-item">', '<label class="layui-form-label" style="width: 70px;">链接地址</label>', '<div class="layui-input-block">', '<input name="url" value="' + (e.href || "") + '" autofocus="true" autocomplete="off" class="layui-input">', "</div>", "</li>", '<li class="layui-form-item">', '<label class="layui-form-label" style="width: 70px;">链接文本</label>', '<div class="layui-input-block">', '<input name="text" value="' + (e.text || "") + '" autofocus="true" autocomplete="off" class="layui-input" ' + ("" !== e.text ? 'readonly="readonly"' : "") + ">", "</div>", "</li>", '<li class="layui-form-item ' + (o ? "" : "layui-hide") + '">', '<label class="layui-form-label" style="width: 70px;">打开方式</label>', '<div class="layui-input-block">', '<input type="radio" name="target" value="_blank" class="layui-input" title="新窗口" ' + ("_blank" === e.target ? "checked" : "") + ">", '<input type="radio" name="target" value="_self" class="layui-input" title="当前窗口"' + ("_self" !== e.target && e.target ? "" : "checked") + ">", "</div>", "</li>", '<li class="layui-form-item ' + (o ? "" : "layui-hide") + '">', '<label class="layui-form-label" style="width: 70px;">rel属性</label>', '<div class="layui-input-block">', '<input type="radio" name="rel" value="" class="layui-input" title="无" ' + ("" !== e.rel && e.target ? "" : "checked") + ">", '<input type="radio" name="rel" value="nofollow" class="layui-input" title="nofollow"' + ("nofollow" === e.rel ? "checked" : "") + ">", "</div>", "</li>", '<button type="button" lay-submit lay-filter="layedit-link-yes" id="layedit-link-yes" class="layui-btn" style="display: none;"> 确定 </button>', "</ul>"].join(""),
                    btn: ["确定", "取消"],
                    btnAlign: "c",
                    yes: function (t, e) {
                        i("#layedit-link-yes").click()
                    },
                    btn1: function (t, e) {
                        n.close(t),
                            setTimeout(function () {
                                s.focus()
                            }, 10)
                    },
                    success: function (e, i) {
                        l.render("radio"),
                            l.on("submit(layedit-link-yes)", function (e) {
                                n.close(t.index),
                                a && a(e.field)
                            })
                    }
                });
            t.index = d
        },
        x = function t(e, a) {
            var o = n.open({
                type: 1,
                id: "LAY_layedit_customlink",
                area: function () {
                    return /mobile/i.test(navigator.userAgent) || i(window).width() <= 350 ? ["90%"] : ["350px"]
                }(),
                offset: function () {
                    return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                }(),
                shade: .05,
                shadeClose: !0,
                moveType: 1,
                title: e.title,
                skin: "layui-layer-msg",
                content: ['<ul class="layui-form" style="margin: 15px;">', '<li class="layui-form-item">', '<label class="layui-form-label" style="width: 60px;">名称</label>', '<div class="layui-input-block" style="margin-left: 90px">', '<input name="text" value="" autofocus="true" autocomplete="off" class="layui-input">', "</div>", "</li>", '<li class="layui-form-item" style="display:none">', '<button type="button" lay-submit lay-filter="layedit-link-yes" id="layedit-link-yes"> 确定 </button>', "</li>", "</ul>"].join(""),
                btn: ["确定", "取消"],
                btnAlign: "c",
                yes: function (t, e) {
                    i("#layedit-link-yes").click()
                },
                success: function (e, i) {
                    l.render("radio"),
                        l.on("submit(layedit-link-yes)", function (e) {
                            a && a(e.field),
                                n.close(t.index)
                        })
                }
            });
            t.index = o
        },
        b = function t(e, a) {
            var o = n.open({
                type: 1,
                id: "LAY_layedit_addmd",
                area: function () {
                    return /mobile/i.test(navigator.userAgent) || i(window).width() <= 350 ? ["90%"] : ["350px"]
                }(),
                offset: function () {
                    return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                }(),
                shade: .05,
                shadeClose: !0,
                moveType: 1,
                title: "添加锚点",
                skin: "layui-layer-msg",
                content: ['<ul class="layui-form" style="margin: 15px;">', '<li class="layui-form-item">', '<label class="layui-form-label" style="width: 60px;">名称</label>', '<div class="layui-input-block" style="margin-left: 90px">', '<input name="text" value="' + (e.name || "") + '" autofocus="true" autocomplete="off" class="layui-input">', "</div>", "</li>", '<button type="button" lay-submit lay-filter="layedit-link-yes" id="layedit-link-yes" class="layui-btn" style="display: none;"> 确定 </button>', "</ul>"].join(""),
                btn: ["确定", "取消"],
                btnAlign: "c",
                yes: function (t, e) {
                    i("#layedit-link-yes").click()
                },
                success: function (e, i) {
                    l.render("radio"),
                        l.on("submit(layedit-link-yes)", function (e) {
                            n.close(t.index),
                            a && a(e.field)
                        })
                }
            });
            t.index = o
        },
        _ = function t(l) {
            return /mobile/i.test(navigator.userAgent) ? t.index = n.open({
                type: 1,
                title: !1,
                closeBtn: 0,
                shade: .05,
                shadeClose: !0,
                content: '<div style="padding: 5px;border: 1px solid #e6e6e6;"><span id="laytable_label" class="layui-label">0列 x 0行</span><table class="layui-table" lay-size="sm"><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></table ></div></div > ',
                area: ["85%"],
                skin: "layui-box layui-util-face",
                success: function (t, a) {
                    t.find("td").on("touchmove", function (n) {
                        var l = e(n);
                        if (null != l && "TD" === l.tagName.toUpperCase()) {
                            t.find("#laytable_label")[0].innerText = l.cellIndex + 1 + "列X" + (l.parentElement.rowIndex + 1) + "行",
                                t.find("td").removeAttr("style"),
                                i(l).attr("style", "background-color:linen;"),
                                i(l).prevAll().attr("style", "background-color:linen;");
                            for (var a = 0; a < i(l.parentElement).prevAll().length; a++) for (var o = 0; o < i(l.parentElement).prevAll()[a].childNodes.length; o++) o <= l.cellIndex && (i(l.parentElement).prevAll()[a].children[o].style = "background-color:linen;")
                        }
                    }),
                        t.find("td").on("touchend", function (t) {
                            var i = e(t);
                            null != i && "TD" === i.tagName.toUpperCase() && (l && l({
                                cells: i.cellIndex + 1,
                                rows: i.parentElement.rowIndex
                            }), n.close(a))
                        })
                }
            }) : t.index = n.tips('<div style="padding: 5px;border: 1px solid #e6e6e6;"><span id="laytable_label" class="layui-label">0列 x 0行</span><table class="layui-table" lay-size="sm"><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr style="height: 20px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></table ></div></div > ', this, {
                tips: 1,
                time: 0,
                skin: "layui-box layui-util-face",
                maxWidth: 500,
                success: function (e, a) {
                    e.find("td").on("mouseover", function () {
                        e.find("#laytable_label")[0].innerText = this.cellIndex + 1 + "列X" + (this.parentElement.rowIndex + 1) + "行",
                            e.find("td").removeAttr("style"),
                            i(this).attr("style", "background-color:linen;"),
                            i(this).prevAll().attr("style", "background-color:linen;");
                        for (var t = 0; t < i(this.parentElement).prevAll().length; t++) for (var n = 0; n < i(this.parentElement).prevAll()[t].childNodes.length; n++) n <= this.cellIndex && (i(this.parentElement).prevAll()[t].children[n].style = "background-color:linen;")
                    }),
                        e.find("td").on("click", function () {
                            l && l({
                                cells: this.cellIndex + 1,
                                rows: this.parentElement.rowIndex
                            }),
                                n.close(a)
                        }),
                        i(document).off("click", t.hide).on("click", t.hide)
                }
            })
        },
        T = function t(e, l) {
            var a = function () {
                var t = ["[微笑]", "[嘻嘻]", "[哈哈]", "[可爱]", "[可怜]", "[挖鼻]", "[吃惊]", "[害羞]", "[挤眼]", "[闭嘴]", "[鄙视]", "[爱你]", "[泪]", "[偷笑]", "[亲亲]", "[生病]", "[太开心]", "[白眼]", "[右哼哼]", "[左哼哼]", "[嘘]", "[衰]", "[委屈]", "[吐]", "[哈欠]", "[抱抱]", "[怒]", "[疑问]", "[馋嘴]", "[拜拜]", "[思考]", "[汗]", "[困]", "[睡]", "[钱]", "[失望]", "[酷]", "[色]", "[哼]", "[鼓掌]", "[晕]", "[悲伤]", "[抓狂]", "[黑线]", "[阴险]", "[怒骂]", "[互粉]", "[心]", "[伤心]", "[猪头]", "[熊猫]", "[兔子]", "[ok]", "[耶]", "[good]", "[NO]", "[赞]", "[来]", "[弱]", "[草泥马]", "[神马]", "[囧]", "[浮云]", "[给力]", "[围观]", "[威武]", "[奥特曼]", "[礼物]", "[钟]", "[话筒]", "[蜡烛]", "[蛋糕]"],
                    i = {};
                return layui.each(t, function (t, n) {
                    i[n] = e.facePath + "images/face/" + t + ".gif"
                }),
                    i
            }();
            return t.hide = t.hide ||
                function (e) {
                    "face" !== i(e.target).attr("layedit-event") && n.close(t.index)
                },
                /mobile/i.test(navigator.userAgent) ? t.index = n.open({
                    type: 1,
                    title: !1,
                    closeBtn: 0,
                    shade: .05,
                    shadeClose: !0,
                    content: function () {
                        var t = [];
                        return layui.each(a, function (e, i) {
                            t.push('<li title="' + e + '"><img src="' + i + '" alt="' + e + '"/></li>')
                        }),
                        '<ul class="layui-clear" style="width: 279px;">' + t.join("") + "</ul>"
                    }(),
                    skin: "layui-box layui-util-face",
                    success: function (t, e) {
                        t.find(".layui-clear>li").on("click", function () {
                            l && l({
                                src: a[this.title],
                                alt: this.title
                            }),
                                n.close(e)
                        })
                    }
                }) : t.index = n.tips(function () {
                    var t = [];
                    return layui.each(a, function (e, i) {
                        t.push('<li title="' + e + '"><img src="' + i + '" alt="' + e + '"/></li>')
                    }),
                    '<ul class="layui-clear" style="width: 279px;">' + t.join("") + "</ul>"
                }(), this, {
                    tips: 1,
                    time: 0,
                    skin: "layui-box layui-util-face",
                    maxWidth: 500,
                    success: function (e, o) {
                        e.css({
                            marginTop: -4,
                            marginLeft: -10
                        }).find(".layui-clear>li").on("click", function () {
                            l && l({
                                src: a[this.title],
                                alt: this.title
                            }),
                                n.close(o)
                        }),
                            i(document).off("click", t.hide).on("click", t.hide)
                    }
                })
        },
        E = function t(e, l) {
            t.hide = t.hide ||
                function (e) {
                    "fontFomatt" == i(e.target).attr("layedit-event") || "fontfamily" == i(e.target).attr("layedit-event") || n.close(t.index)
                },
                t.index = n.tips(function () {
                    var t = [];
                    return layui.each(e.fonts, function (i, n) {
                        t.push('<li title="' + e.fonts[i] + '" style="float: initial;width:100%;"><' + e.fonts[i] + ">" + e.texts[i] + "</" + e.fonts[i] + "></li>")
                    }),
                    '<ul class="layui-clear" style="width: max-content;">' + t.join("") + "</ul>"
                }(), this, {
                    tips: 1,
                    time: 0,
                    skin: "layui-box layui-util-face",
                    success: function (a, o) {
                        a.css({
                            marginTop: -4,
                            marginLeft: -10
                        }).find(".layui-clear>li").on("click", function () {
                            l && l(this.title, e.fonts),
                                n.close(o)
                        }),
                            i(document).off("click", t.hide).on("click", t.hide)
                    }
                })
        },
        k = function (t, e) {
            E.hide = E.hide ||
                function (t) {
                    "fontFomatt" == i(t.target).attr("layedit-event") || "fontfamily" == i(t.target).attr("layedit-event") || "fontSize" == i(t.target).attr("layedit-event") || n.close(E.index)
                },
                E.index = n.tips(function () {
                    var e = [];
                    return layui.each(t.fonts, function (i, n) {
                        e.push('<li title="' + t.fonts[i] + '" style="float: initial;width:100%;' + t.fonts[i] + '"><' + t.fonts[i] + ">" + t.texts[i] + "</" + t.fonts[i] + "></li>")
                    }),
                    '<ul class="layui-clear" style="width: max-content;">' + e.join("") + "</ul>"
                }(), this, {
                    tips: 1,
                    time: 0,
                    skin: "layui-box layui-util-face",
                    success: function (l, a) {
                        l.css({
                            marginTop: -4,
                            marginLeft: -10
                        }).find(".layui-clear>li").on("click", function () {
                            e && e(this.title, t.fonts),
                                n.close(a)
                        }),
                            i(document).off("click", E.hide).on("click", E.hide)
                    }
                })
        },
        w = function (t, e) {
            E.hide = E.hide ||
                function (t) {
                    "fontFomatt" == i(t.target).attr("layedit-event") || "fontfamily" == i(t.target).attr("layedit-event") || "fontSize" == i(t.target).attr("layedit-event") || n.close(E.index)
                },
                E.index = n.tips(function () {
                    var e = [];
                    return layui.each(t.fonts, function (i, n) {
                        e.push('<li title="' + t.fonts[i] + '" style="float: initial;width:100%;' + t.fonts[i] + '"><' + t.fonts[i] + ">" + t.texts[i] + "</" + t.fonts[i] + "></li>")
                    }),
                    '<ul class="layui-clear" style="width: max-content;">' + e.join("") + "</ul>"
                }(), this, {
                    tips: 1,
                    time: 0,
                    skin: "layui-box layui-util-face",
                    success: function (l, a) {
                        l.css({
                            marginTop: -4,
                            marginLeft: -10
                        }).find(".layui-clear>li").on("click", function () {
                            e && e(this.title, t.fonts),
                                n.close(a)
                        }),
                            i(document).off("click", E.hide).on("click", E.hide)
                    }
                })
        },
        A = function t(e, a) {
            var o = ['<li class="layui-form-item objSel">', '<label class="layui-form-label">请选择语言</label>', "<style>#selectCodeLanguage ~ .layui-form-select > dl {max-height: 192px} </style>", '<div class="layui-input-block">', '<select name="lang" id="selectCodeLanguage">', '<option value="JavaScript">JavaScript</option>', '<option value="HTML">HTML</option>', '<option value="CSS">CSS</option>', '<option value="Java">Java</option>', '<option value="PHP">PHP</option>', '<option value="C#">C#</option>', '<option value="Python">Python</option>', '<option value="Ruby">Ruby</option>', '<option value="Go">Go</option>', "</select>", "</div>", "</li>"].join("");
            e.hide && (o = ['<li class="layui-form-item" style="display:none">', '<label class="layui-form-label">请选择语言</label>', '<div class="layui-input-block">', '<select name="lang">', '<option value="' + e.default + '" selected="selected">', e.default, "</option>", "</select>", "</div>", "</li>"].join(""));
            var s = this,
                d = n.open({
                    type: 1,
                    id: "LAY_layedit_code",
                    area: function () {
                        return /mobile/i.test(navigator.userAgent) || i(window).width() <= 650 ? ["90%"] : ["650px"]
                    }(),
                    offset: function () {
                        return /mobile/i.test(navigator.userAgent) ? "auto" : "100px"
                    }(),
                    shade: .05,
                    shadeClose: !0,
                    moveType: 1,
                    title: "插入代码",
                    skin: "layui-layer-msg",
                    content: ['<ul class="layui-form layui-form-pane" style="margin: 15px;">', o, '<li class="layui-form-item layui-form-text">', '<label class="layui-form-label">代码</label>', '<div class="layui-input-block">', '<textarea name="code" lay-verify="required" autofocus="true" class="layui-textarea" style="height: 200px;"></textarea>', "</div>", "</li>", '<button type="button" id="layedit-code-yes" lay-submit lay-filter="layedit-code-yes" class="layui-btn" style="display: none"> 确定 </button>', "</ul>"].join(""),
                    btn: ["确定", "取消"],
                    btnAlign: "c",
                    yes: function (t, e) {
                        i("#layedit-code-yes").click()
                    },
                    btn1: function (t, e) {
                        n.close(t),
                            s.focus()
                    },
                    success: function (i, o) {
                        l.render("select"),
                            l.on("submit(layedit-code-yes)", function (i) {
                                n.close(t.index),
                                a && a(i.field, e.hide, e.default)
                            })
                    }
                });
            t.index = d
        },
        C = {
            html: '<i class="layui-icon layedit-tool-html" title="HTML源代码"  layedit-event="html"">&#xe64b;</i><span class="layedit-tool-mid"></span>',
            undo: '<i class="layui-icon layedit-tool-undo" title="撤销" lay-command="undo" layedit-event="undo"">&#xe603;</i>',
            redo: '<i class="layui-icon layedit-tool-redo" title="重做" lay-command="redo" layedit-event="redo"">&#xe602;</i>',
            strong: '<i class="layui-icon layedit-tool-b" title="加粗" lay-command="Bold" layedit-event="b"">&#xe62b;</i>',
            italic: '<i class="layui-icon layedit-tool-i" title="斜体" lay-command="italic" layedit-event="i"">&#xe644;</i>',
            underline: '<i class="layui-icon layedit-tool-u" title="下划线" lay-command="underline" layedit-event="u"">&#xe646;</i>',
            del: '<i class="layui-icon layedit-tool-d" title="删除线" lay-command="strikeThrough" layedit-event="d"">&#xe64f;</i>',
            "|": '<span class="layedit-tool-mid"></span>',
            left: '<i class="layui-icon layedit-tool-left" title="左对齐" lay-command="justifyLeft" layedit-event="left"">&#xe649;</i>',
            center: '<i class="layui-icon layedit-tool-center" title="居中对齐" lay-command="justifyCenter" layedit-event="center"">&#xe647;</i>',
            right: '<i class="layui-icon layedit-tool-right" title="右对齐" lay-command="justifyRight" layedit-event="right"">&#xe648;</i>',
            link: '<i class="layui-icon layedit-tool-link" title="插入链接" layedit-event="link"">&#xe64c;</i>',
            unlink: '<i class="layui-icon layedit-tool-unlink layui-disabled" title="清除链接" lay-command="unlink" layedit-event="unlink"" style="font-size:18px">&#xe64d;</i>',
            face: '<i class="layui-icon layedit-tool-face" title="表情" layedit-event="face"" style="font-size:18px">&#xe650;</i>',
            image: '<i class="layui-icon layedit-tool-image" title="图片" layedit-event="image" style="font-size:18px">&#xe64a;<input type="file" name="file"></i>',
            code: '<i class="layui-icon layedit-tool-code" title="插入代码" layedit-event="code" style="font-size:18px">&#xe64e;</i>',
            images: '<i class="layui-icon layedit-tool-images" title="多图上传" layedit-event="images" style="font-size:18px">&#xe634;</i>',
            image_alt: '<i class="layui-icon layedit-tool-image_alt" title="图片" layedit-event="image_alt" style="font-size:18px">&#xe64a;</i>',
            video: '<i class="layui-icon layedit-tool-video" title="插入视频" layedit-event="video" style="font-size:18px">&#xe6ed;</i>',
            fullScreen: '<i class="layui-icon layedit-tool-fullScreen" title="全屏" layedit-event="fullScreen"style="font-size:18px">&#xe638;</i>',
            colorpicker: '<i class="layui-icon" title="字体颜色选择" id="layFontColor_Index">&#xe66a;</i>',
            fontBackColor: '<i class="layui-icon" title="字体背景色选择" id="layBkColor_Index"></i>',
            fontFomatt: '<i class="layui-icon layedit-tool-fontFomatt" title="段落格式" layedit-event="fontFomatt" style="font-size:18px">&#xe639;</i>',
            fontFamily: '<i class="layui-icon layedit-tool-fontFamily" title="字体" layedit-event="fontFamily" style="font-size:18px">&#xe702;</i>',
            fontSize: '<i class="layui-icon layedit-tool-fontSize" title="字体大小" layedit-event="fontSize" style="font-size:18px">&#xe642;</i>',
            addhr: '<i class="layui-icon layui-icon-chart layedit-tool-addhr" title="添加水平线" layedit-event="addhr" style="font-size:18px"></i>',
            anchors: '<i class="layui-icon layedit-tool-anchors" title="添加锚点" layedit-event="anchors" style="font-size:18px">&#xe60b;</i>',
            customlink: '<i class="layui-icon layedit-tool-customlink" title="添加自定义链接" layedit-event="customlink" style="font-size:18px">&#xe606;</i>',
            table: '<i class="layui-icon layedit-tool-table" title="插入表格" layedit-event="table" style="font-size:18px">&#xe62d;</i>',
            attachment: '<i class="layui-icon layedit-tool-attachment" title="插入附件" layedit-event="attachment" style="font-size:18px">&#xe62f;</i>',
            fontfamily: '<i class="layui-icon layedit-tool-fontfamily" title="字体" layedit-event="fontfamily" style="font-size:18px">&#xe642;</i>',
            help: '<i class="layui-icon layedit-tool-help" title="帮助" layedit-event="help">&#xe607;</i>'
        },
        N = new s;
    l.render(),
        t("layedit", N)
});
