/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

let currentPage = 2;

export default (options) => {
    let tpl = require('../html/result_list.html');

    $("#load-more a").addClass('d-none');
    $("#load-more i").removeClass('d-none');

    if ($("#load-more").hasClass("d-none")) {
        return;
    }

    $.ajax({
        url: $('#load-more').attr('data-load-more-url'),
        method: "GET",
        data: {
            ccm_paging_p: currentPage,
        },
        success: (response) => {
            let html = tpl(response);

            $("#karma-container").append(html);

            $("#load-more a").removeClass('d-none');
            $("#load-more i").addClass('d-none');

            if (!response.hasNextPage) {
                $("#load-more").addClass("d-none")
            } else {
                currentPage++;
            }
        }
    });
}