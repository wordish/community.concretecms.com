/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

// Custom assets
import _ from 'underscore'
window._ = _
import fetchResults from './result_list';

if ($(".karma-page").length > 0) {
    $("#load-more a").click(function () {
        fetchResults();
    });

    $(window).scroll(function () {
        if ($(document).height() - $(this).height() == $(this).scrollTop()) {
            fetchResults();
        }
    });
}
