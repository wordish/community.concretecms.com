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
        $(".members-search .dropdown .form-check label").click(function (e) {
            let $checkbox = $(this).prev();

            if ($checkbox.is(":checked")) {
                $checkbox.prop("checked", false);
            } else {
                $checkbox.prop("checked", true);
            }

            e.preventDefault();

            return false;
        });
    });
})(jQuery);
