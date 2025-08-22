$(document).ready(function () {
    $(document).on('click', '#add_agent, #back_btn', function () {
        swapTableAndCreation();
        getAgentCode();

    });

    //////////////////////////////////////////////////////////////// Submit Agent Creation Start ///////////////////////////////////////////////////////////////////

    $('#submit_agent_creation').click(function (event) {
        event.preventDefault();
        //Validation
        let agent_code = $('#agent_code').val(); let agent_name = $('#agent_name').val(); let mobile1 = $('#mobile1').val(); let mobile2 = $('#mobile2').val(); let area = $('#area').val(); let occupation = $('#occupation').val(); let agent_id = $('#agent_id').val();

        var data = ['agent_code', 'agent_name', 'mobile1']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this agent creation?',
                function () {
                    $.post('api/agent_creation_files/submit_agent_creation.php', { agent_code, agent_name, mobile1, mobile2, area, occupation, agent_id }, function (response) {
                        if (response === '2') {
                            swalSuccess('Success', 'Agent Added Successfully!');
                        } else if (response === '1') {
                            swalSuccess('Success', 'Agent Updated Successfully!');
                        } else {
                            swalError('Error', 'Error Occurred!');
                        }
                        $('#agent_id').val('');
                        $('#agent_creation').trigger('reset');
                        getAgentTable();
                        swapTableAndCreation();//to change to div to table content.

                    });
                }
            );

        }
    });

    //////////////////////////////////////////////////////////////// Submit Agent Creation End ////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////// Edit Agent Creation Start ////////////////////////////////////////////////////////////////////////

    $(document).on('click', '.agentActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/agent_creation_files/agent_creation_data.php', { id: id }, function (response) {
            swapTableAndCreation();
            $('#agent_id').val(id);
            $('#agent_code').val(response[0].agent_code);
            $('#agent_name').val(response[0].agent_name);
            $('#mobile1').val(response[0].mobile1);
            $('#mobile2').val(response[0].mobile2);
            $('#area').val(response[0].area);
            $('#occupation').val(response[0].occupation);

        }, 'json');
    });

    //////////////////////////////////////////////////////////////// Edit Agent Creation End ////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////// Delete Agent Creation Start ////////////////////////////////////////////////////////////////////////

    $(document).on('click', '.agentDeleteBtn', function () {
        var id = $(this).attr('value');
        swalConfirm('Delete', 'Do you want to Delete the Agent Details?', getAgentDelete, id);
        return;
    });

    //////////////////////////////////////////////////////////////// Delete Agent Creation End ////////////////////////////////////////////////////////////////////////


    $('#mobile1, #mobile2').change(function () {
        checkMobileNo($(this).val(), $(this).attr('id'));
    });

    $('button[type="reset"], #back_btn').click(function (event) {
        event.preventDefault();
        $('input').each(function () {
            $(this).val('');
        });
        $('input').css('border', '1px solid #cecece');
    });

}) /////////////////////////////////////////////////////////////////////////////// document end ///////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////// Function Start ///////////////////////////////////////////////////////////////////////

$(function () {
    getAgentTable()
});

function swapTableAndCreation() {
    if ($('.agent_table_content').is(':visible')) {
        $('.agent_table_content').hide();
        $('#add_agent').hide();
        $('#agent_creation_content').show();
        $('#back_btn').show();

    } else {
        $('.agent_table_content').show();
        $('#add_agent').show();
        $('#agent_creation_content').hide();
        $('#back_btn').hide();
    }
}

function getAgentTable() {
    $.post('api/agent_creation_files/agent_creation_list.php', function (response) {
        var columnMapping = [
            'sno',
            'agent_code',
            'agent_name',
            'area',
            'occupation',
            'mobile1',
            'action'
        ];
        appendDataToTable('#agent_create', response, columnMapping);
        setdtable('#agent_create');

    }, 'json')
}

function getAgentCode() {
    $.ajax({
        url: 'api/agent_creation_files/getAgentCode.php',
        type: "post",
        dataType: "json",
        data: {},
        cache: false,
        success: function (response) {
            var agent_code = response;
            $('#agent_code').val(agent_code);
        }
    })
}

function getAgentDelete(id) {
    $.post('api/agent_creation_files/delete_agent_creation.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('Success', 'Agent Deleted Successfully!');
            getAgentTable();
        } else {
            swalError('Error', 'Failed to Delete Agent: ' + response);
        }
    }, 'json');
}

//////////////////////////////////////////////////////////////////////////// Function End ///////////////////////////////////////////////////////////////////////////