/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

/*
 * Add search functionality for teams
 */

(function ($) {
    $(function () {
        $('.ccm-teams-search')
            .selectpicker({
                liveSearch: true
            })
            .ajaxSelectPicker({
                ajax: {
                    url: CCM_DISPATCHER_FILENAME + '/api/v1/teams/search',
                    data: function () {
                        return {
                            keywords: '{{{q}}}'
                        };
                    }
                },
                preprocessData: function (data) {
                    var teams = [];

                    if (data.hasOwnProperty('teams')) {
                        var len = data.teams.length;
                        for (var i = 0; i < len; i++) {
                            var team = data.teams[i];
                            teams.push(
                                {
                                    'value': team.gID,
                                    'text': team.gName,
                                    'disabled': false
                                }
                            );
                        }
                    }
                    return teams;
                },
                preserveSelected: false
            });
    });
})(jQuery);