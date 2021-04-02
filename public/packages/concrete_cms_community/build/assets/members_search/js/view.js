/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

(function ($) {
    $(function () {
        /*
         * This little hack is required to prevent closing the bootstrap dropdown window when clicking on a label of a checkbox.
         */
        $(".members-search .ccm-dropdown-menu .form-check label").click(function (e) {
            let $checkbox = $(this).prev();

            if ($checkbox.is(":checked")) {
                $checkbox.prop("checked", false);
            } else {
                $checkbox.prop("checked", true);
            }

            e.preventDefault();

            return false;
        });

        $(".members-search .toggle-dropdown").click(function (e) {
            let $dropdown = $(".ccm-dropdown-menu");

            if ($dropdown.hasClass("d-none")) {
                $dropdown.removeClass("d-none");
                $(this).addClass("active");
                $dropdown.css("top", $(this).position().top + 68);
            } else {
                $dropdown.addClass("d-none");
                $(this).removeClass("active");
            }

            e.preventDefault();

            return false;
        });

        $(window).resize(function () {
            $(".members-search .ccm-dropdown-menu:not(.d-none)").css("top", $(".members-search .toggle-dropdown").position().top + 68);
        });
    });
})(jQuery);
