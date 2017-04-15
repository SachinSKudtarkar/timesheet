jQuery(function ($) {
    jQuery(document).on('click', '#throughput-passed-sites-grid a.approve', function () {
        var url = jQuery(this).attr('href');
        var page_name = $(this).data('val');
        $.confirm({
            backgroundDismiss: true,
            content: 'Are you sure you want to approve ENodeB ?',
            confirm: function () {
                var th = this,
                        afterApplicable = function () {
                        };
                jQuery('#throughput-passed-sites-grid').yiiGridView('update', {
                    type: 'POST',
                    url: url,
                    data: {page_name: page_name, },
                    success: function (data) {
                        jQuery('#throughput-passed-sites-grid').yiiGridView('update');
                        afterApplicable(th, true, data);
                    },
                    error: function (XHR) {
                        return afterApplicable(th, false, XHR);
                    }
                });
            },
            cancel: function () {
            }
        });
        return false;
    });

    jQuery(document).on('click', '#throughput-passed-sites-grid a.reject', function () {
        var url = jQuery(this).attr('href');
        var page_name = $(this).data('val');

        $.confirm({
            backgroundDismiss: true,
            content: 'Are you sure you want to reject ENodeB ?',
            confirm: function () {
                var th = this,
                        afterApplicable = function () {
                        };
                jQuery('#throughput-passed-sites-grid').yiiGridView('update', {
                    type: 'POST',
                    url: url,
                    data: {page_name: page_name, },
                    success: function (data) {
                        jQuery('#throughput-passed-sites-grid').yiiGridView('update');
                        afterApplicable(th, true, data);
                    },
                    error: function (XHR) {
                        return afterApplicable(th, false, XHR);
                    }
                });
            },
            cancel: function () {
            }
        });
        return false;
    });

    jQuery(document).on('click', '#approve_btn', function () {
        var url = "/release/ThroughputPassedSites/ApproveAllCheckedENodeB";
        var selectedChk = $.fn.yiiGridView.getSelection('throughput-passed-sites-grid');

        if (selectedChk == '' || selectedChk == null) {
            alert("select enodeb check box first");
            return false;
        }
        $.confirm({
            backgroundDismiss: true,
            content: 'Are you sure you want to approve selected ENodeB ?',
            confirm: function () {
                var th = this,
                        afterApplicable = function () {
                        };
//                    var selectedChk = $.fn.yiiGridView.getChecked('throughput-passed-sites-grid',columnID);
                // returns key values of CHECKED rows

                jQuery('#throughput-passed-sites-grid').yiiGridView('update', {
                    type: 'POST',
                    url: url,
//                        dataType: 'application/json',
                    data: {selectedChk1: selectedChk, },
                    success: function (data) {
                        jQuery('#throughput-passed-sites-grid').yiiGridView('update');
                        afterApplicable(th, true, data);
                    },
                    error: function (XHR) {
                        return afterApplicable(th, false, XHR);
                    }
                });
            },
            cancel: function () {
            }
        });
        return false;
    });

    jQuery(document).on('click', '#reject_btn', function () {
        var url = "/release/ThroughputPassedSites/RejectAllCheckedENodeB";
        var selectedChk = $.fn.yiiGridView.getSelection('throughput-passed-sites-grid');

        if (selectedChk == '' || selectedChk == null) {
            alert("select enodeb check box first");
            return false;
        }
        $.confirm({
            backgroundDismiss: true,
            content: 'Are you sure you want to reject selected ENodeB ?',
            confirm: function () {
                var th = this,
                        afterApplicable = function () {
                        };
//                    var selectedChk = $.fn.yiiGridView.getChecked('throughput-passed-sites-grid',columnID);
                // returns key values of CHECKED rows

                jQuery('#throughput-passed-sites-grid').yiiGridView('update', {
                    type: 'POST',
                    url: url,
//                        dataType: 'application/json',
                    data: {selectedChk1: selectedChk, },
                    success: function (data) {
                        jQuery('#throughput-passed-sites-grid').yiiGridView('update');
                        afterApplicable(th, true, data);
                    },
                    error: function (XHR) {
                        return afterApplicable(th, false, XHR);
                    }
                });
            },
            cancel: function () {
            }
        });
        return false;
    });

    jQuery(document).on('click', '.chkOutput', function () {
        var url = BASE_URL + "/ThroughputPassedSites/fetchOutput";


        $('#content').animate({
            scrollTop: $("#content").scrollTop() + 0
        }, 500);

        $('#content').html('');
        var id = $(this).data('val');
        var linkName = $(this).data('linkname');

        $.ajax({
            url: BASE_URL + "/ThroughputPassedSites/fetchOutput",
            type: 'POST',
            dataType: 'json',
            data: {id: id, linkName: linkName},
            beforeSend: function () {
//                          $('#CompareBtn').val("Comparing please wait...");
            },
            complete: function () {

            },
            success: function (data)
            {
                if (data.status == "ERROR")
                {

                }
                if (data.status == "SUCCESS")
                {
                    $('#content').html(data.output);
                    $('#myModalLabel').html(data.head);
                }
            },
            error: function (XMLHttpRequest, data, errorThrown) {
            },
        });
        return false;
    });

    jQuery(document).on('click', '#submit_btn', function () {
        var url = "/rjilauto-prod/ThroughputPassedSites/SubmitToQaTeam";

        $.confirm({
            backgroundDismiss: true,
            content: 'Are you sure you want to submit ENodeB to QA?',
            confirm: function () {
                var th = this,
                        afterApplicable = function () {
                        };
                filert_date = $("#filter_date").val();
                jQuery('#throughput-passed-sites-grid').yiiGridView('update', {
                    type: 'POST',
                    url: url,
//                        dataType: 'application/json',
                    data: {filert_date: filert_date, },
                    success: function (data) {
                        jQuery('#throughput-passed-sites-grid').yiiGridView('update');
                        afterApplicable(th, true, data);
                    },
                    error: function (XHR) {
                        return afterApplicable(th, false, XHR);
                    }
                });
            },
            cancel: function () {
            }
        });
        return false;
    });
});

$("#checkall11").click(function () {

    var checked_status = true;

    if ($(this).hasClass('checked'))
    {
        checked_status = false;
        $(this).addClass('unchecked');
        $(this).removeClass('checked');
    }
    else
    {
        checked_status = true;
        $(this).addClass('checked');
        $(this).removeClass('unchecked');
    }

    $("input[type=checkbox]").each(function () {

        this.checked = checked_status;
//            change(this);
    });
});