<script>
(function (a) {
    a.tiny = a.tiny || {};
    a.tiny.scrollbar = {
        options: {
            axis: "y",
            wheel: 40,
            scroll: true,
            lockscroll: true,
            size: "auto",
            sizethumb: "auto",
            invertscroll: false
        }
    };
    a.fn.tinyscrollbar = function (d) {
        var c = a.extend({}, a.tiny.scrollbar.options, d);
        this.each(function () {
            a(this).data("tsb", new b(a(this), c))
        });
        return this
    };
    a.fn.tinyscrollbar_update = function (c) {
        return a(this).data("tsb").update(c)
    };

    function b(q, g) {
        var k = this,
            t = q,
            j = {
                obj: a(".menu-viewport", q)
            }, h = {
                obj: a(".menu-overview", q)
            }, d = {
                obj: a(".menu-scrollbar", q)
            }, m = {
                obj: a(".menu-track", d.obj)
            }, p = {
                obj: a(".menu-thumb", d.obj)
            }, l = g.axis === "x",
            n = l ? "left" : "top",
            v = l ? "Width" : "Height",
            r = 0,
            y = {
                start: 0,
                now: 0
            }, o = {}, e = "ontouchstart" in document.documentElement;

        function c() {
            k.update();
            s();
            return k
        }
        this.update = function (z) {
            j[g.axis] = j.obj[0]["offset" + v];
            h[g.axis] = h.obj[0]["scroll" + v];
            h.ratio = j[g.axis] / h[g.axis];
            d.obj.toggleClass("disable", h.ratio >= 1);
            m[g.axis] = g.size === "auto" ? j[g.axis] : g.size;
            p[g.axis] = Math.min(m[g.axis], Math.max(0, (g.sizethumb === "auto" ? (m[g.axis] * h.ratio) : g.sizethumb)));
            d.ratio = g.sizethumb === "auto" ? (h[g.axis] / m[g.axis]) : (h[g.axis] - j[g.axis]) / (m[g.axis] - p[g.axis]);
            r = (z === "relative" && h.ratio <= 1) ? Math.min((h[g.axis] - j[g.axis]), Math.max(0, r)) : 0;
            r = (z === "bottom" && h.ratio <= 1) ? (h[g.axis] - j[g.axis]) : isNaN(parseInt(z, 10)) ? r : parseInt(z, 10);
            w()
        };

        function w() {
            var z = v.toLowerCase();
            p.obj.css(n, r / d.ratio);
            h.obj.css(n, -r);
            o.start = p.obj.offset()[n];
            d.obj.css(z, m[g.axis]);
            m.obj.css(z, m[g.axis]);
            p.obj.css(z, p[g.axis])
        }

        function s() {
            if (!e) {
                p.obj.bind("mousedown", i);
                m.obj.bind("mouseup", u)
            } else {
                j.obj[0].ontouchstart = function (z) {
                    if (1 === z.touches.length) {
                        i(z.touches[0]);
                        z.stopPropagation()
                    }
                }
            } if (g.scroll && window.addEventListener) {
                t[0].addEventListener("DOMMouseScroll", x, false);
                t[0].addEventListener("mousewheel", x, false);
                t[0].addEventListener("MozMousePixelScroll", function (z) {
                    z.preventDefault()
                }, false)
            } else {
                if (g.scroll) {
                    t[0].onmousewheel = x
                }
            }
        }

        function i(A) {
            a("body").addClass("noSelect");
            var z = parseInt(p.obj.css(n), 10);
            o.start = l ? A.pageX : A.pageY;
            y.start = z == "auto" ? 0 : z;
            if (!e) {
                a(document).bind("mousemove", u);
                a(document).bind("mouseup", f);
                p.obj.bind("mouseup", f)
            } else {
                document.ontouchmove = function (B) {
                    B.preventDefault();
                    u(B.touches[0])
                };
                document.ontouchend = f
            }
        }

        function x(B) {
            if (h.ratio < 1) {
                var A = B || window.event,
                    z = A.wheelDelta ? A.wheelDelta / 120 : -A.detail / 3;
                r -= z * g.wheel;
                r = Math.min((h[g.axis] - j[g.axis]), Math.max(0, r));
                p.obj.css(n, r / d.ratio);
                h.obj.css(n, -r);
                if (g.lockscroll || (r !== (h[g.axis] - j[g.axis]) && r !== 0)) {
                    A = a.event.fix(A);
                    A.preventDefault()
                }
            }
        }

        function u(z) {
            if (h.ratio < 1) {
                if (g.invertscroll && e) {
                    y.now = Math.min((m[g.axis] - p[g.axis]), Math.max(0, (y.start + (o.start - (l ? z.pageX : z.pageY)))))
                } else {
                    y.now = Math.min((m[g.axis] - p[g.axis]), Math.max(0, (y.start + ((l ? z.pageX : z.pageY) - o.start))))
                }
                r = y.now * d.ratio;
                h.obj.css(n, -r);
                p.obj.css(n, y.now)
            }
        }

        function f() {
            a("body").removeClass("noSelect");
            a(document).unbind("mousemove", u);
            a(document).unbind("mouseup", f);
            p.obj.unbind("mouseup", f);
            document.ontouchmove = document.ontouchend = null
        }
        return c()
    }
}(jQuery));
</script>
<?php
	$selected_tab = elgg_extract("type", $vars, "newsfeed");
	$page_owner_guid = elgg_get_page_owner_guid();
	$group = get_entity($page_owner_guid);
	$group_name = $group->name;
	
	$tabs = array();
	$tabs[] = array(
		"text" => elgg_echo("newsfeed"),
		"href" => "groups/profile/" . $page_owner_guid . "/" . $group_name,
		"link_id" => "file-tools-single-form-link",
		"selected" => ($selected_tab == "newsfeed") ? true: false
	);
	$tabs[] = array(
		"text" => elgg_echo("members"),
		"href" => "groups/members/" . $page_owner_guid,
		"link_id" => "file-tools-single-form-link",
		"selected" => ($selected_tab == "members") ? true: false
	);
	if ($group->file_enable == "yes") {
		$tabs[] = array(
			"text" => elgg_echo("files"),
			"href" => "file/group/" . $page_owner_guid . "/all",
			"link_id" => "file-tools-single-form-link",
			"is_trusted" => true,
			"selected" => ($selected_tab == "files") ? true: false
		);
	}
	if ($group->polls_enable == "yes") {
		$tabs[] = array(
			"text" => elgg_echo("polls"),
			"href" => "polls/group/" . $page_owner_guid . "/all",
			"link_id" => "polls-single-form-link",
			"selected" => ($selected_tab == "polls") ? true: false
		);
	}
	if ($group->pages_enable == "yes") {
		$tabs[] = array(
			"text" => elgg_echo("pages"),
			"href" => "pages/group/" . $page_owner_guid . "/all",
			"link_id" => "pages-single-form-link",
			"selected" => ($selected_tab == "pages") ? true: false
		);
	}
	if ($group->blog_enable == "yes") {
		$tabs[] = array(
			"text" => elgg_echo("blog:blogs"),
			"href" => "blog/group/" . $page_owner_guid . "/all",
			"link_id" => "file-tools-single-form-link",
			"selected" => ($selected_tab == "blogs") ? true: false
		);
	}
	if ($group->event_manager_enable != "no") {
		$tabs[] = array(
			"text" => elgg_echo("event_manager:menu:title"),
			"href" => "events/event/list/" . $page_owner_guid,
			"link_id" => "eventmanager-single-form-link",
			"selected" => ($selected_tab == "events") ? true: false
		);
	}
	if ($group->thewire_enable != "no") {
		$tabs[] = array(
			"text" => elgg_echo("thewire"),
			"href" => "thewire_group/group/" . $page_owner_guid . "/all",
			"link_id" => "thewire-single-form-link",
			"selected" => ($selected_tab == "thewire") ? true: false
		);
	}
	if ($group->forum_enable == "yes") {
		$tabs[] = array(
			"text" => elgg_echo("profile:discussion"),
			"href" => "discussion/owner/" . $page_owner_guid . "/all",
			"link_id" => "file-tools-single-form-link",
			"selected" => ($selected_tab == "discussion") ? true: false
		);
	}
	if ($group->bookmarks_enable == "yes") {
		$tabs[] = array(
			"text" => elgg_echo("bookmarks"),
			"href" => "bookmarks/group/" . $page_owner_guid . "/all",
			"link_id" => "bookmark-single-form-link",
			"selected" => ($selected_tab == "bookmarks") ? true: false
		);
	}
foreach ($tabs as $name => $tab) {
        $tab['name'] = $name;

        elgg_register_menu_item('group_filter', $tab);
}
?>
<div id='elgg-menu-group-filter-container'>
<div class="menu-scrollbar"><div class="menu-track"><div class="menu-thumb"><div class="end"></div></div></div></div>
<div class="menu-viewport"><div class="menu-overview">
<?php
echo elgg_view_menu('group_filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz tabs'));
?>
</div></div></div>
<script>
var menuScrollbar = $('#elgg-menu-group-filter-container');
menuScrollbar.tinyscrollbar({ axis: "x"});
menuScrollbar.tinyscrollbar_update({size: 'auto'});
</script>
