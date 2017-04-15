$(document).ready(function(){

    $("#OnecParser").validate({
        errorElement:'div',
        rules: {
                topology: {
                        required: true
                },
                sub_topology: {
                        required: true
                },
                region: {
                        required: true
                },
                city: {
                        required: true
                },
                'folder[]': {
                        required: true
                }

        },

        messages: {
                topology: {
                        required: "Please select topology"
                },
                sub_topology: {
                        required: "Please select sub-topology"
                },
                region: {
                        required: "Please select region"
                },
                city: {
                        required: "Please select city"
                },
                'folder[]': {
                        required: "Please select folder"
                }
        }
    });
});
