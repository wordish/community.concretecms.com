(()=>{var e;(e=jQuery)((function(){e(".ccm-teams-search").selectpicker({liveSearch:!0}).ajaxSelectPicker({ajax:{url:CCM_DISPATCHER_FILENAME+"/api/v1/teams/search",data:function(){return{keywords:"{{{q}}}"}}},preprocessData:function(e){var a=[];if(e.hasOwnProperty("teams"))for(var r=e.teams.length,t=0;t<r;t++){var s=e.teams[t];a.push({value:s.gID,text:s.gName,disabled:!1})}return a},preserveSelected:!1})}))})();