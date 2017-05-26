$(document).ready(function() {

    //PLUG INS
    //toggle function on click jQuery plug in
    (function($) {
        $.fn.clickToggle = function(func1, func2) {
            var funcs = [func1, func2];
            this.data('toggleclicked', 0);
            this.click(function() {
                var data = $(this).data();
                var tc = data.toggleclicked;
                $.proxy(funcs[tc], this)();
                data.toggleclicked = (tc + 1) % 2;
            });
            return this;
        };
    }(jQuery));
    //toggle function on click jQuery plug in

    //END PLUG INS

    //UI Functions CLass
    var action = new Actions();
    action.getNumOfRecords();
    //Total records function
    $("#totalRecs").html('<h1>' + action.total + '</h1>');
    //UI Functions CLass

    $('.callsRadioContainer input[name=calls_outcome_filter]').on('change', function() {
        var keyw = $(this).val();
        var cid = $('#client_id').val();
        action.renderCalls(cid, keyw);
    });

    //ADD ACCUSED FUNCTIONS
    $('#addAccusedBtn').on('click', function() {
        document.getElementById('accusedForm').reset();
        var aId = action.ID('A');
        $('.accused_id').text(aId);
        $('#accused_id').val(aId);
        $('.accusedModal').fadeIn();
    });

    $('#accusedSaveBtn').on('click', function() {
        var data = $("#accusedForm").serialize();

        $.ajax({
            data: data,
            async: false,
            type: "post",
            url: "src/dataHandler.php?q=addAccused&client_id=" + $('#client_id').val(),
            success: function(data) {
                if (data == 'Success!') {
                    action.renderAccused();
                    $('.accusedModal').fadeOut();
                }
            }
        });
    });

    $('.accusedModalCloseBtn').on('click', function() {
        $('.accusedModal').fadeOut();
    });

    $('.accusedMainContainer').on('click', '.accusedBoxContainer .accusedBoxCloseBtn', function() {
        var aid = $(this).siblings('.aid').val();
        var conf = window.confirm('Accused: ' + aid + ' is about to be destroyed and it can not be undone. Would you like to proceed?');

        if (conf) {
            $.ajax({
                async: false,
                type: "post",
                url: "src/dataHandler.php?q=delAccused&accused_id=" + aid,
                success: function(data) {
                    var mes = document.createElement('div');
                    if (data == 'Success!') {
                        mes.className = 'messageBox alert alert-success alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! Accused ID: ' + aid + ' deleted.';
                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                        action.renderAccused();
                        $('.form-loading').fadeOut();
                    } else {
                        mes.className = 'messageBox alert alert-danger alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Err!</strong> Sorry.. Something went wrong.' + data;
                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                        $('.form-loading').fadeOut();

                    }
                }
            });
        }

    });


    $('.accusedMainContainer').on('click', '.accusedBoxContainer .accusedModalUpdateBtn', function() {
            $('.accusedBoxMainLoading').show();
            var aid = $(this).attr('data-aid');
            var data = $("#accusedUpdateForm_" + aid).serialize();

            $.ajax({
                data: data,
                async: false,
                type: "post",
                url: "src/dataHandler.php?q=updateAccused&accused_id=" + aid,
                success: function(data) {
                    var mes = document.createElement('div');
                    if (data == 'Success!') {
                        mes.className = 'messageBox alert alert-success alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! Accused ID: ' + aid + ' updated.';
                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                        action.renderAccused();
                        $('.accusedBoxMainLoading').fadeOut();
                    } else {
                        mes.className = 'messageBox alert alert-danger alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Err!</strong> Sorry.. Something went wrong.' + data;
                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                        $('.accusedBoxMainLoading').fadeOut();

                    }
                }
            });
        })
        //END ADD ACCUSED FUNCTIONS




    //CALLS FUNCTIONS
    $('.callsModalSaveBtn').on('click', function() {
        $('.form-loading').fadeIn();
        var valid = $('#calls-form').parsley().validate();
        if (valid) {
            var cid = $('#addCallBtn').attr('data-cid');
            var data = $("#calls-form").serialize();
            $.ajax({
                data: data,
                async: false,
                type: "post",
                url: "src/dataHandler.php?q=addCall&client_id=" + cid,
                success: function(data) {
                    var mes = document.createElement('div');
                    if (data == 'Success!') {
                        mes.className = 'messageBox alert alert-success alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! call added to client ID: ' + cid;
                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                        action.renderCalls(cid, 'all');
                        $('.form-loading').fadeOut();
                    } else {
                        mes.className = 'messageBox alert alert-danger alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Err!</strong> Sorry.. Something went wrong.' + data;
                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                        $('.form-loading').fadeOut();

                    }
                }
            });

        }

    });

    $('.callsModalCloseBtn').on('click', function() {
        $('.calls_modal').fadeOut();
    });

    $('.addCallBtn').on('click', function() {
        document.getElementById("calls-form").reset();
        $('.calls_modal').fadeIn();
    });
    //CALLS FUNCTIONS

    //report date range function
    $('#to').on('change', function() {
        if ($('#from').val() !== '' && $('#to').val() !== '') {
            $('#reports').parent().removeClass('col-md-5');
            $('#client-data').fadeOut();
            $('#reports div').remove();
            $('#reports').fadeIn();
            $.ajax({
                type: "get",
                url: "src/reports.php?rq=period&rpf=" + $('#from').val() + '&rpt=' + $('#to').val(),
                success: function(data) {

                    var result = JSON.parse(data);
                    var keys = Object.keys(result);

                    $.each(result, function(k, v) {
                        var total = 0;
                        html = '<div style="margin:0px !important;padding:0px !important" class="col-md-12"><div class="reportContainer">' +
                            '<div class="reportTitle row">' +
                            '<div class="col-sm-12">' +
                            '<h4>' + k.split('.')[0] + ' <small>' + (new Date(k.split('.')[1])).toDateString() + '</small></h4>' +
                            '</div>' +
                            '</div>' +
                            '<div class="reportContent">';
                        $.each(v, function(key, val) {
                            total += val;
                            html = html + '<div class="row">' +
                                '<div class="col-sm-10">' +
                                '<p>' + key + '</p>' +
                                '</div>' +
                                '<div class="col-sm-2">' +
                                '<p>' + val + '</p>' +
                                '</div></div>';

                        });
                        html = html + '<div class="row">' +
                            '<div class="col-sm-10">' +
                            '<p><strong>Total: </strong></p>' +
                            '</div>' +
                            '<div class="col-sm-2">' +
                            '<p><strong>' + total + '</strong></p>' +
                            '</div></div>';

                        html = html + '</div></div></div>';

                        $('#reports').append(html);

                    });

                }
            });
        }
    })

    //get all data on load function
    $(function() {
        $('.main-loading').show();
        $.ajax({
            async: false,
            type: "get",
            url: "src/dataHandler.php?q=all",
            success: function(data) {
                var d = JSON.parse(data);
                action.genData(d);
                $('.main-loading').fadeOut();
            }
        });
    });
    //end get all data on load function

    //main page action btns
    $('#splitBtn').on('click', function() {
        $('#dataRows').addClass('dataRows-split');
        $('#client-data').addClass('col-md-7');
        $('#reports div').remove();
        $('#reports').parent().addClass('col-md-5');
        $('#reports').fadeIn();
        $('#client-data').fadeIn();
        $.ajax({
            type: "get",
            url: "src/reports.php?rq=all",
            success: function(data) {
                console.log(data);
                var result = JSON.parse(data);
                var keys = Object.keys(result);

                $.each(result, function(k, v) {
                    var total = 0;
                    html = '<div style="margin:0px !important;padding:0px !important" class="col-md-12"><div class="reportContainer">' +
                        '<div class="reportTitle row">' +
                        '<div class="col-sm-12">' +
                        '<h4>' + k.split('.')[0] + ' <small>' + (new Date(k.split('.')[1])).toDateString() + '</small></h4>' +
                        '</div>' +
                        '</div>' +
                        '<div class="reportContent">';
                    $.each(v, function(key, val) {
                        total += val;
                        html = html + '<div class="row">' +
                            '<div class="col-sm-10">' +
                            '<p>' + key + '</p>' +
                            '</div>' +
                            '<div class="col-sm-2">' +
                            '<p>' + val + '</p>' +
                            '</div></div>';

                    });
                    html = html + '<div class="row">' +
                        '<div class="col-sm-10">' +
                        '<p><strong>Total: </strong></p>' +
                        '</div>' +
                        '<div class="col-sm-2">' +
                        '<p><strong>' + total + '</strong></p>' +
                        '</div></div>';

                    html = html + '</div></div></div>';

                    $('#reports').append(html);

                });

            }
        });

    });

    $('#reportsBtn').on('click', function() {
        $('#reports').parent().removeClass('col-md-5');
        $('#client-data').fadeOut();
        $('#reports div').remove();
        $('#reports').fadeIn();
        $.ajax({
            type: "get",
            url: "src/reports.php?rq=all",
            success: function(data) {
                var result = JSON.parse(data);
                var keys = Object.keys(result);

                $.each(result, function(k, v) {
                    var total = 0;
                    html = '<div class="col-md-12"><div class="reportContainer">' +
                        '<div class="reportTitle row">' +
                        '<div class="col-sm-12">' +
                        '<h4>' + k.split('.')[0] + ' <small>' + (new Date(k.split('.')[1])).toDateString() + '</small></h4>' +
                        '</div>' +
                        '</div>' +
                        '<div class="reportContent">';
                    $.each(v, function(key, val) {
                        total += val;
                        html = html + '<div class="row">' +
                            '<div class="col-sm-10">' +
                            '<p>' + key + '</p>' +
                            '</div>' +
                            '<div class="col-sm-2">' +
                            '<p>' + val + '</p>' +
                            '</div></div>';

                    });
                    html = html + '<div class="row">' +
                        '<div class="col-sm-10">' +
                        '<p><strong>Total: </strong></p>' +
                        '</div>' +
                        '<div class="col-sm-2">' +
                        '<p><strong>' + total + '</strong></p>' +
                        '</div></div>';

                    html = html + '</div></div></div>';

                    $('#reports').append(html);

                });

            }
        });

    });

    $('#clientsBtn').on('click', function() {
        $('#reports').fadeOut();
        $('#client-data').removeClass('col-md-7');
        $('#client-data').fadeIn();
    });
    //main page action btns

    //DOCUMENT FUNCTIONS
    //function to open uploaded document
    $('.documentsList').on('click', '.fileBtn', function() {
        var path = document.location.hostname;
        window.open('src/uploads/' + $(this).attr('data-fileFolder') + '/' + $(this).attr('data-fileName'));
    });

    $('.documentsList').on('click', '.fileBtnDel', function() {
        $('.form-loading').show();
        $.ajax({
            type: "get",
            url: 'src/dataHandler.php?q=delFile&client_id=' + $('.fileBtnDel').attr('data-fileFolder') + '&fileName=' + $('.fileBtnDel').attr('data-fileName'),
            success: function(d) {
                var mes = document.createElement('div');
                if (d == 'Success!') {
                    mes.className = 'messageBox alert alert-success alert-dismissable fade in';
                    mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong>File: ' + $('.fileBtnDel').attr('data-fileName') + ' from client ID: ' + $('.fileBtnDel').attr('data-fileFolder') + ' was deleted succesfully!';
                    $('[data-fileName="' + $('.fileBtnDel').attr('data-fileName') + '"]').each(function() {
                        $(this).parent().remove();
                    });
                    document.getElementById('messageContainer').appendChild(mes);
                    window.setTimeout(function() {
                        $(".messageBox").alert('close');
                    }, 5000);

                    $('.form-loading').fadeOut();
                } else {
                    mes.className = 'messageBox alert alert-danger alert-dismissable fade in';
                    mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Err!</strong> Sorry.. Something went wrong.' + data;
                    document.getElementById('messageContainer').appendChild(mes);
                    window.setTimeout(function() {
                        $(".messageBox").alert('close');
                    }, 5000);
                    $('.form-loading').fadeOut();

                }


            }
        });



    });
    //END DOCUMENT FUNCTIONS

    //client charges select function
    $("#client_charges").chosen({
        width: '100%'
    });


    //Main form checkbox function
    $('#main-form').on('click', 'input[type=checkbox]', function() {
        if ($(this).prop('checked') === false) {
            $(this).val(0);
        } else {
            $(this).val(1);
        }
    });
    //END Main form checkbox function

    //Accused form checkbox function
    $('#accusedForm').on('click', 'input[type=checkbox]', function() {
        if ($(this).prop('checked') === false) {
            $(this).val(0);
        } else {
            $(this).val(1);
        }
    });
    //END Accused form checkbox function


    //function to prevent record from being altered
    $('.protectLabel i').on('click', function() {
        if ($(this).attr('class') == 'fa fa-unlock') {
            $(this).removeClass('fa fa-unlock');
            $(this).attr('class', 'fa fa-lock');
            $(this).css('color', 'orangered');
            $('#main-form input').attr('disabled', true);
            $('#main-form textarea').attr('disabled', true);
            $('#main-form select').attr('disabled', true);
            $('#accusedForm input').attr('disabled', true);
            $('#accusedForm textarea').attr('disabled', true);
            $('#accusedForm select').attr('disabled', true);
            $('#calls-form input').attr('disabled', true);
            $('#calls-form textarea').attr('disabled', true);
            $('#calls-form select').attr('disabled', true);
            $('#accusedSaveBtn').hide();
            $('#updateBtn').hide();
            $('.callsModalSaveBtn').hide();
            $('.accusedModalUpdateBtn').hide();
        } else if ($(this).attr('class') == 'fa fa-lock') {
            $(this).removeClass('fa fa-lock');
            $(this).attr('class', 'fa fa-unlock');
            $(this).css('color', '#8799ad');
            $('#main-form input').attr('disabled', false);
            $('#main-form textarea').attr('disabled', false);
            $('#main-form select').attr('disabled', false);
            $('#accusedForm input').attr('disabled', false);
            $('#accusedForm textarea').attr('disabled', false);
            $('#accusedForm select').attr('disabled', false);
            $('#calls-form input').attr('disabled', false);
            $('#calls-form textarea').attr('disabled', false);
            $('#calls-form select').attr('disabled', false);
            $('#updateBtn').show();
            $('.callsModalSaveBtn').show();
            $('.accusedModalUpdateBtn').show();
            $('#accusedSaveBtn').show();
        }
    });


    //Generate new id function
    $('#genIdBtn').on('click', function() {
        document.getElementById("main-form").reset();
        $('#client_charges').trigger('chosen:updated');
        var cId = action.ID('C');
        $('.client_id').text(cId);
        $('#client_id').val(cId);
    });


    //PRINT FUNCTIONS
    $('#printBtn').on('click', function() {
        $.ajax({
            data: 'client_id=' + $(this).attr('data-print-id'),
            type: "get",
            url: "src/dataHandler.php?q=form",
            success: function(d) {
                var data = JSON.parse(d);
                action.print(data);
                $('.main-loading').fadeOut();
            }
        });

    });
    //PRINT FUNCTIONS

    //CLOCK
    var timerInt = setInterval(function() {
        var today = new Date();
        var hours = today.getHours();
        var minutes = today.getMinutes();
        var s = today.getSeconds();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ':' + s + ' ' + ampm;
        $('#time').text(strTime);
    }, 1000);
    //END CLOCK

    //table sorting function
    $('#headers div i').on('click', function() {
        if ($(this).attr('class') == 'glyphicon glyphicon-triangle-bottom') {
            $('.main-loading').show();
            $(this).removeClass('glyphicon-triangle-bottom');
            $(this).addClass('glyphicon-triangle-top');
            var el = $(this).attr('id');
            var sql = "ORDER BY " + el + " DESC";
            $.ajax({

                type: "get",
                url: "src/dataHandler.php?q=comp&sql=" + sql,
                success: function(data) {
                    var d = JSON.parse(data);

                    action.total = d.length;
                    action.genData(d);
                    $('.main-loading').fadeOut();

                }
            });

        } else {
            $('.main-loading').show();
            $(this).removeClass('glyphicon-triangle-top');
            $(this).addClass('glyphicon-triangle-bottom');
            var el = $(this).attr('id');
            var sql = "ORDER BY " + el + " ASC";
            $.ajax({

                type: "get",
                url: "src/dataHandler.php?q=comp&sql=" + sql,
                success: function(data) {
                    var d = JSON.parse(data);

                    action.total = d.length;
                    action.genData(d);
                    $('.main-loading').fadeOut();

                }
            });

        }
    });
    //end table sorting function

    //refresh table button function
    $('#client-data').on('click', '#refreshCont button', function() {
        $('.main-loading').show();
        $.ajax({
            type: "get",
            url: "src/dataHandler.php?q=all",
            success: function(data) {
                var d = JSON.parse(data);
                action.genData(d);
                $('.main-loading').fadeOut();
            }
        });
    });
    //end refresh table button function

    //SEARCH BOX FUNCTION
    $('#search').on('keyup', function() {
        if ($('#search').val().length > 0) {
            $('.main-loading').show();
            $.ajax({

                type: "get",
                url: "src/dataHandler.php?q=search&keyword=" + $('#search').val(),
                success: function(data) {
                    var d = JSON.parse(data);
                    action.genData(d);
                    $('.main-loading').fadeOut();

                }
            });
        } else {
            $('.main-loading').show();
            $.ajax({

                type: "get",
                url: "src/dataHandler.php?q=all",
                success: function(data) {
                    var d = JSON.parse(data);
                    action.genData(d);
                    $('.main-loading').fadeOut();

                }
            });
        }
    });
    //END SEARCH BOX FUNTION

    //CRUD FUNCTIONS
    //update function
    $('#updateBtn').on('click', function() {
        $('.documentsList .fileNameContainer').remove();
        var e = $('#client_charges').val();
        var data = $("#main-form").serialize();


        var id = $('#client_id').val();
        action.file(id);
        $('.form-loading').show();

        $.ajax({
            data: data,
            type: "post",
            url: "src/dataHandler.php?q=update",
            success: function(data) {
                var mes = document.createElement('div');
                if (data == 'Success!') {
                    mes.className = 'messageBox alert alert-success alert-dismissable fade in';
                    mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Client:' + id + ' saved successfully.';

                    document.getElementById('messageContainer').appendChild(mes);
                    window.setTimeout(function() {
                        $(".messageBox").alert('close');
                    }, 3000);
                    $('.form-loading').fadeOut();
                } else {
                    mes.className = 'messageBox alert alert-danger alert-dismissable fade in';
                    mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Err!</strong> Sorry.. Something went wrong. ';
                    document.getElementById('messageContainer').appendChild(mes);
                    window.setTimeout(function() {
                        $(".messageBox").alert('close');
                    }, 3000);
                    $('.form-loading').fadeOut();
                }

            }
        });
    });
    //creat function
    $('#saveBtn').click(function() {
        var valid = $('#main-form').parsley().validate();
        if (valid) {
            $('.form-loading').show();
            var id = $('#client_id').val();
            action.file(id);
            var data = $("#main-form").serialize();
            $.ajax({
                data: data,
                type: "post",
                url: "src/dataHandler.php?q=add",
                success: function(data) {
                    var mes = document.createElement('div');
                    if (data == 'Success!') {
                        mes.className = 'messageBox alert alert-success alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Client:' + id + ' was created successfully.';
                        document.getElementById('messageContainer').appendChild(mes);
                        //Total records function
                        action.getNumOfRecords();
                        $("#totalRecs").html('<h1>' + action.total + '</h1>');
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 5000);
                        $('.form-loading').fadeOut();
                    } else if (data == 'duplicate') {
                        mes.className = 'messageBox alert alert-warning alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning!</strong> Incident number already in database. Record not saved.';
                        document.getElementById('messageContainer').appendChild(mes);
                        $('#police_incident_id').css('backgroundColor', 'red !important');
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 13000);
                        $('.form-loading').fadeOut();
                    } else {
                        mes.className = 'messageBox alert alert-danger alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Err!</strong> Sorry.. Something went wrong.' + data;
                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 13000);
                        $('.form-loading').fadeOut();

                    }


                }
            });
        }
    });
    //END CRUD FUNCTIONS

    //add clientBtn function to open form
    $('#addBtn').on('click', function() {
        $('.accusedBoxContainer').remove();
        $('.callsTab').hide();
        $('.accusedModal').hide();
        $('.calls_modal').hide();
        $('.documentsList .fileNameContainer').remove();
        $('#main-form')[0].reset();
        $('#client_charges').trigger('chosen:updated');
        $('.nav-tabs li').removeClass('active main');
        $('.tab-pane').removeClass('active');
        $('.tab-pane:nth-child(1)').addClass('active');
        $('.nav-tabs li:nth-child(1)').addClass('active main');
        $('.tab').tab('show');
        var d = new Date();
        var cId = action.ID('C');
        $('.client_id').text(cId);
        $('#client_id').val(cId);
        $('#saveBtn').show();
        $('#updateBtn').hide();
        $('#printBtn').hide();
        $('#genIdBtn').show();
        $(".protectCont").hide();
    });
    //END add clientBtn function to open form


    //ajax function to populate client form: .form-wrapper
    $('#dataRows').on('click', '.dataRow div .openRecBtn', function() {
        $('.accusedTab').show();
        $('.callsTab').show();
        $('#main-form')[0].reset();
        $('.nav-tabs li').removeClass('active main');
        $('.tab-pane').removeClass('active');
        $('.tab-pane:nth-child(1)').addClass('active');
        $('.nav-tabs li:nth-child(1)').addClass('active main');
        $('.form-loading').show();
        var cid = $(this).attr('data-client');
        action.renderCalls(cid, 'all');
        action.getFiles(cid);
        $('#genIdBtn').hide();
        $('#saveBtn').hide();
        $('#updateBtn').show();
        $('#printBtn').show();
        $(".protectCont").show()
        $.ajax({
            data: "client_id=" + cid,
            type: "get",
            url: "src/dataHandler.php?q=form",
            async: false,
            success: function(data) {
                $('.form-loading').fadeOut();

                action.renderForm(data);

            }
        });
    });
    //end ajax function to populate client form: .form-wrapper


    //Main Form Navigation Tabs
    $('.tab').on('click', function(e) {
        e.preventDefault()
        $(this).tab('show')
    });

    //Calculate client age range on intake date change
    $('#main-form #client_intake_date').on('change', function() {
        action.calculateAgeRange();
    });

});


//---------------------ACTION CLASS---------------------------  >>
var Actions = function main() {
        //props
        this.total = 0;
        this.limit = 15;
        this.page = 1;
        this.links = 0;
        this.tKeys = [];

        //methods
        this.ID = function(user) {
            var id;
            $.ajax({
                type: "get",
                url: "src/dataHandler.php?q=genId",
                async: false,
                success: function(data) {
                    id = data;
                }
            });
            return user + id;
        };
        this.genData = function(data) {
            $('#dataRows .dataRow').fadeOut();
            $('#dataRows .dataRow').remove();
            if (this.total > 0) {
                for (var i = 0; i < data.length; i++) {
                    var d = new Date(data[i].client_intake_date).toDateString();
                    $('#dataRows').prepend('<div class="row dataRow"><div class="col-md-1">' + data[i].client_id + '</div>' +
                        '<div class="col-md-2"><p>' + data[i].client_name + ' ' + data[i].client_surname + '</p></div>' +
                        '<div class="col-md-1">' + ((data[i].client_gender == 'Male') ? '<i class="fa fa-mars" style="color: blue; text-align: center; font-size: 20pt;" aria-hidden="true"></i>' :
                            (data[i].client_gender == 'Female') ? '<i class="fa fa-venus" style="color: pink; text-align: center; font-size: 20pt;" aria-hidden="true"></i>' : '<i class="fa fa-transgender" style="color: #5bcefa; text-align: center; font-size: 20pt;" aria-hidden="true"></i>') + '</div>' +
                        '<div class="col-md-2">' + data[i].client_age_range + '</div>' +
                        '<div class="col-md-2">' + d + '</div>' +
                        '<div class="col-md-1">' + data[i].police_incident_id + '</div>' +
                        '<div class="col-md-1"><button data-toggle="modal" data-target="#mainForm" data-client="' + data[i].client_id + '" class="openRecBtn" type="button">Open   <i class="glyphicon glyphicon-folder-open"></i></button></div>' +
                        '</div>');
                }
                $('#dataRows .dataRow').css('opacity', '1');

            } else {
                $('#dataRows').append('<div class="row dataRow"><div style="text-align: center;" class="col-md-12">No records to show..</div></div>');
                $('#dataRows .dataRow').css('opacity', '1');

            }

        }
        this.calculateAgeRange = function() {
            var dob = $('#main-form #client_dob').val();
            var intake = $('#main-form #client_intake_date').val();

            function parse(d) {
                var result = d.split('-');
                return new Date(result[0], result[1], result[2]);
            }

            function diff(dob, intake) {
                var timeDiff = Math.abs(dob.getTime() - intake.getTime());
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                return Math.abs(dob.getFullYear() - intake.getFullYear());
            }

            if (diff(parse(dob), parse(intake)) < 16) {
                $('#client_age_range').val('Under 16')
            } else if (diff(parse(dob), parse(intake)) >= 16 && diff(parse(dob), parse(intake)) <= 19) {
                $('#client_age_range').val('16-19')
            } else if (diff(parse(dob), parse(intake)) >= 20 && diff(parse(dob), parse(intake)) <= 25) {
                $('#client_age_range').val('20-25')
            } else if (diff(parse(dob), parse(intake)) >= 26 && diff(parse(dob), parse(intake)) <= 45) {
                $('#client_age_range').val('26-45')
            } else if (diff(parse(dob), parse(intake)) >= 46 && diff(parse(dob), parse(intake)) <= 65) {
                $('#client_age_range').val('46-65')
            } else if (diff(parse(dob), parse(intake)) > 65) {
                $('#client_age_range').val('65+')
            }
        }
        this.renderForm = function(data) {

            var d = JSON.parse(data);
            if (d.length > 0) {
                var ch = [];
                this.tKeys = key;
                var accArr = [];
                for (var j = 0; j < d.length; j++) {

                    var key = Object.keys(d[j]);

                    for (var i = 0; i < key.length; i++) {
                        if (key[i] == 'client_charges') {

                            //$("#client_charges option[value='" + d[0][key[i]].split(',') + "']").prop('selected', true);

                            //$("#client_charges").val(d[0][key[i]].split(',')).trigger('chosen:updated');
                            ch.push(d[0][key[i]].split(','));


                        }
                        if (key[i] == 'police_incident_id') {
                            $('#mainForm #police_incident_id').val(d[0][key[i]]);

                        }
                        if (key[i].substr(0, 2) == 'ac' && key[i] == 'accused_id') {
                            accArr.push(d[j][key[i]]);

                        }
                        if (key[i] == 'client_id') {
                            $('#mainForm .' + key[i]).text(d[0][key[i]]);
                            $('#mainForm #' + key[i]).val(d[0][key[i]]);
                            $('#printBtn').attr('data-print-id', d[0][key[i]]);
                            $('#addCallBtn').attr('data-cid', d[0][key[i]]);

                        } else {
                            if ($('#mainForm #' + key[i]).attr('type') == 'checkbox' && $('#mainForm #' + key[i]).prop('checked') == 0 && d[0][key[i]] == 1) {
                                $('#mainForm #' + key[i]).prop("checked", true);

                            } else if ($('#mainForm #' + key[i]).attr('type') == 'checkbox' && d[0][key[i]] == 0) {
                                $('#mainForm #' + key[i]).prop("checked", false);

                            }
                            if (key[i] == 'client_home_phone') {
                                $('#mainForm #' + key[i]).val(d[0][key[i]]);
                                $('#mainForm .client-phone-number').text(d[0][key[i]]);

                            }
                            $('#mainForm #' + key[i]).val(d[0][key[i]]);

                        }


                    }
                }
                if (ch[0].length > 0) {
                    $("#client_charges").val(ch[0]).trigger('chosen:updated');
                } else {
                    $("#client_charges").val('').trigger('chosen:updated');
                    console.log(ch);
                }


                ch = [];
                this.renderAccused();

            }

        }
        this.print = function(data) {

            var html = '<div id="printContainer">' +

                '<div class="clientInfo">' +
                '<div class="conTitle">' +
                '<div class="innerTitle">' +
                '<h4>Client   <i class="glyphicon glyphicon-info-sign"></i></h4>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Client ID: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_id + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Name: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_name + ' ' + data[0].client_surname + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">D.O.B: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_dob + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p>Age Range: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_age_range + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Gender: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_gender + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Home Phone: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_home_phone + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Alt-Phone: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_alt_phone + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Address: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_address + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Indigenus: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + ((data[0].client_indigenous == 1) ? 'YES' : 'NO') + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Intake Date: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_intake_date + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Alt-Contact: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].client_alt_contact + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Police Incident #: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].police_incident_id + '</p>' +
                '</div>' +
                '</div>' +
                '</div>' +

                //==================================================================================
                '<div class="childrenInfo">' +
                '<div class="conTitle">' +
                '<div class="innerTitle">' +
                '<h4>Children   <i class="glyphicon glyphicon-info-sign"></i></h4>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">children Names: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].children_names + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">CAS Notified Date: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].children_cas_notified_date + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">CAS Worker: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].children_cas_worker + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Outcome: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].children_outcome + '</p>' +
                '</div>' +
                '</div>' +

                '</div>' +

                //==================================================================================
                '<div class="ReferralInfo">' +
                '<div class="conTitle">' +
                '<div class="innerTitle">' +
                '<h4>Referral   <i class="glyphicon glyphicon-info-sign"></i></h4>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Referral ID: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].referral_id + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Active Date: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].referral_active_date + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Referred By: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].referral_reffered_by + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Other Agencies: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].referral_other_agencies + '</p>' +
                '</div>' +
                '</div>' +

                '</div>' +

                //==================================================================================
                '<div class="AccusedInfo">' +
                '<div class="conTitle">' +
                '<div class="innerTitle">' +
                '<h4>Accused   <i class="glyphicon glyphicon-info-sign"></i></h4>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Accused ID: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].accused_id + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Name: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].accused_name + ' ' + data[0].accused_surname + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">D.O.B: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].accused_dob + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Gender: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].accused_gender + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Indigenus: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + ((data[0].accused_indigenous == 1) ? 'YES' : 'NO') + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Young Offender: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + ((data[0].accused_young_offender == 1) ? 'YES' : 'NO') + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Active Date: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].accused_active_date + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Next Court Date: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].accused_next_court_date + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Release Date: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].accused_release_date + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Sentencing: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].accused_sentencing + '</p>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-md-4">' +
                '<p class="secTitle">Outcome: </p>' +
                '</div>' +
                '<div class="col-md-8">' +
                '<p>' + data[0].accused_outcome + '</p>' +
                '</div>' +
                '</div>' +

                '</div>' +




                '</div>';


            my_window = window.open('', 'mywindow', 'status=1,width=600,height=1200');
            my_window.document.write('<html><head><title>Brantford Police Victim Services - Client ID: ' + data[0].client_id + '</title><link href="css/print.css" rel="stylesheet"><link rel="stylesheet" href="css/bootstrap.min.css"></head>');
            my_window.document.write('<body onafterprint="self.close()">');
            my_window.document.write(html);
            my_window.document.write('<button id="print" type="button" class="btn btn-default" onclick="window.print();"><i class="glyphicon glyphicon-print"></i> Print</button>');
            my_window.document.write('<script src="js/jquery-3.1.1.min.js"></script>' +
                '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>' +
                '<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>' +
                '<script src="js/jquery-ui.min.js"></script>' +
                '<script src="js/bootstrap.min.js"></script>' +
                '<script src="js/parsley.min.js"></script>' +
                '<script src="js/app.js"></script>');
            my_window.document.write('</body></html>');


        }
        this.file = function(cid) {
            var theFiles = document.getElementById('client_documents');
            var mainForm = document.getElementById('main-form');
            var files = theFiles.files;
            var formData = new FormData();
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                formData.append('client_documents[]', file, file.name);
            }
            var obj = this;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'src/dataHandler.php?q=file&client_id=' + cid, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var res = xhr.responseText;
                    var mes = document.createElement('div');
                    var messType = res.charAt(0) + '' + res.charAt(1);
                    var realMes = res.replace(messType + ' ', '');
                    if (messType == 'm1') {
                        mes.className = 'messageBox alert alert-success alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + realMes;
                        obj.getFiles(cid);

                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                    }
                    if (messType == 'm2') {
                        mes.className = 'messageBox alert alert-info alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Info!</strong> ' + realMes;
                        obj.getFiles(cid);

                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                    }
                    if (messType == 'm3') {
                        mes.className = 'messageBox alert alert-warning alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning!</strong> ' + realMes;
                        obj.getFiles(cid);

                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                    }
                    if (messType == 'm4') {
                        mes.className = 'messageBox alert alert-danger alert-dismissable fade in';
                        mes.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Err!</strong> ' + realMes;
                        obj.getFiles(cid);

                        document.getElementById('messageContainer').appendChild(mes);
                        window.setTimeout(function() {
                            $(".messageBox").alert('close');
                        }, 3000);
                    }


                } else {
                    alert('An error occurred!');
                }
            };
            xhr.send(formData);
        }
        this.getFiles = function(id) {
            $('.documentsList > .fileNameContainer').remove();
            $.ajax({
                type: "get",
                url: "src/dataHandler.php?q=getFiles&client_id=" + id,
                async: false,
                success: function(data) {

                    if (data == false || JSON.parse(data).length < 3) {
                        $('.documentsList').append('<div class="col-md-6 fileNameContainer"><button type="button">No documents.</button></div>');
                    } else {
                        var d = JSON.parse(data);
                        for (var i = 2; i < d.length; i++) {
                            var element = '<div class="col-md-6 fileNameContainer" style="white-space: nowrap;"><button class="fileBtn" style="margin-bottom: 8px;" type="button"data-fileFolder="' + id + '" data-fileName=' + d[i] + '>' + d[i] + '</button><button type="button" class="fileBtnDel" data-fileFolder="' + id + '" data-fileName="' + d[i] + '">X</button></div>';
                            $('.documentsList').prepend(element);

                        }
                    }



                }
            });
        }
        this.renderCalls = function(id, keyw) {
            $('.calls div').remove();
            if (keyw != 'all') {
                $.ajax({
                    type: "get",
                    url: "src/dataHandler.php?q=callFilter&keyw=" + keyw + "&client_id=" + id,
                    async: false,
                    success: function(data) {
                        if (data == false) {
                            $('.calls').append('<div class="col-md-6 ">No Calls.</div>');
                        } else {
                            var d = JSON.parse(data);
                            for (var i = 0; i < d.length; i++) {
                                var date = new Date(d[i].call_date).toDateString();
                                var html = '<div class="col-md-4" style="position: relative;">' +
                                    '<div class="callContainer row">' +
                                    '<div class="callHeader row">' +

                                    '<div id="call_date" class="col-md-12">' + date + '</div>' +
                                    '</div>' +
                                    '<div class="callActions row">' +
                                    '<div class="col-md-6">';
                                if (d[i].call_outcome == 'Info Calls') {
                                    html = html + '<h4 style="color:silver;" id="call_outcome">' + d[i].call_outcome + ' <i class="glyphicon glyphicon-info-sign"></i></h4>';
                                } else if (d[i].call_outcome == 'Initial Court Call') {
                                    html = html + '<h4 style="color:#333;white-space:nowrap;" id="call_outcome">' + d[i].call_outcome + ' <i class="fa fa-university" aria-hidden="true"></i></h4>';
                                } else if (d[i].call_outcome == 'No Contact') {
                                    html = html + '<h4 style="color:red;" id="call_outcome">' + d[i].call_outcome + ' <i class="glyphicon glyphicon-thumbs-down"></i></h4>';
                                } else if (d[i].call_outcome == 'Message Left') {
                                    html = html + '<h4 style="white-space:nowrap;color:#8799AD;" id="call_outcome">' + d[i].call_outcome + ' <i class="glyphicon glyphicon-inbox"></i></h4>';
                                } else if (d[i].call_outcome == 'Letter Sent') {
                                    html = html + '<h4 style="white-space:nowrap;color:lightblue;" id="call_outcome">' + d[i].call_outcome + ' <i class="glyphicon glyphicon-send"></i></h4>';
                                } else if (d[i].call_outcome == 'Release/Detain Call') {
                                    html = html + '<h4 style="white-space:nowrap;color:#333;" id="call_outcome">' + d[i].call_outcome + ' <i class="fa fa-gavel"></i></h4>';
                                } else if (d[i].call_outcome == 'Remand Call') {
                                    html = html + '<h4 style="white-space:nowrap;color:orangered;" id="call_outcome">' + d[i].call_outcome + ' <i style="font-size:10pt;" class="glyphicon glyphicon-earphone"></i><i class="glyphicon glyphicon-user"></i></h4>';
                                } else {
                                    html = html + '<h4 style="white-space:nowrap;" id="call_outcome">' + d[i].call_outcome + ' </h4>';
                                }

                                html = html + '</div>' +
                                    '<div class="col-md-6"><button class="callNotesBtn" type="button" style="position:absolute !important;top:10px !important;right:0px !important;border:none !important;background:transparent;">Notes  <i class="glyphicon glyphicon-chevron-down"></i></button></div>' +
                                    '</div>' +
                                    '<div class="callNotes row">' +
                                    '<div class="col-md-12">' +
                                    '<p id="call_notes">' + d[i].call_notes + '</p>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                                $('.calls').prepend(html);


                            }
                            $('.callNotesBtn').clickToggle(function() {
                                $(this).parent().parent().parent('.callContainer').css({
                                    position: 'absolute',
                                    height: '220px',
                                    zIndex: '1020',
                                    left: '8px'
                                });
                            }, function() {
                                $(this).parent().parent().parent('.callContainer').css({
                                    height: '80px'
                                });
                                var e = $(this).parent().parent().parent('.callContainer');
                                setTimeout(function() {
                                    e.css({
                                        position: 'static',

                                    });
                                }, 500);
                            });

                        }

                    }
                });
            }

            if (keyw == 'all') {
                $.ajax({
                    type: "get",
                    url: "src/dataHandler.php?q=callFilter&keyw=All&client_id=" + id,
                    async: false,
                    success: function(data) {
                        if (data == false) {
                            $('.calls').append('<div class="col-md-6 ">No Calls.</div>');
                        } else {
                            var d = JSON.parse(data);
                            for (var i = 0; i < d.length; i++) {
                                var html = '<div class="col-md-4" style="position: relative;">' +
                                    '<div class="callContainer row">' +
                                    '<div class="callHeader row">' +

                                    '<div id="call_date" class="col-md-12">' + d[i].call_date + '</div>' +
                                    '</div>' +
                                    '<div class="callActions row">' +
                                    '<div class="col-md-6">';
                                if (d[i].call_outcome == 'Info Calls') {
                                    html = html + '<h4 style="color:silver;" id="call_outcome">' + d[i].call_outcome + ' <i class="glyphicon glyphicon-info-sign"></i></h4>';
                                } else if (d[i].call_outcome == 'Initial Court Call') {
                                    html = html + '<h4 style="color:#333;white-space:nowrap;" id="call_outcome">' + d[i].call_outcome + ' <i class="fa fa-university" aria-hidden="true"></i></h4>';
                                } else if (d[i].call_outcome == 'No Contact') {
                                    html = html + '<h4 style="color:red;" id="call_outcome">' + d[i].call_outcome + ' <i class="glyphicon glyphicon-thumbs-down"></i></h4>';
                                } else if (d[i].call_outcome == 'Message Left') {
                                    html = html + '<h4 style="white-space:nowrap;color:#8799AD;" id="call_outcome">' + d[i].call_outcome + ' <i class="glyphicon glyphicon-inbox"></i></h4>';
                                } else if (d[i].call_outcome == 'Letter Sent') {
                                    html = html + '<h4 style="white-space:nowrap;color:lightblue;" id="call_outcome">' + d[i].call_outcome + ' <i class="glyphicon glyphicon-send"></i></h4>';
                                } else if (d[i].call_outcome == 'Release/Detain Call') {
                                    html = html + '<h4 style="white-space:nowrap;color:#333;" id="call_outcome">' + d[i].call_outcome + ' <i class="fa fa-gavel"></i></h4>';
                                } else if (d[i].call_outcome == 'Remand Call') {
                                    html = html + '<h4 style="white-space:nowrap;color:orangered;" id="call_outcome">' + d[i].call_outcome + ' <i style="font-size:10pt;" class="glyphicon glyphicon-earphone"></i><i class="glyphicon glyphicon-user"></i></h4>';
                                } else {
                                    html = html + '<h4 style="white-space:nowrap;" id="call_outcome">' + d[i].call_outcome + ' </h4>';
                                }

                                html = html + '</div>' +
                                    '<div class="col-md-6"><button class="callNotesBtn" type="button" style="position:absolute !important;top:10px !important;right:0px !important;border:none !important;background:transparent;">Notes  <i class="glyphicon glyphicon-chevron-down"></i></button></div>' +
                                    '</div>' +
                                    '<div class="callNotes row">' +
                                    '<div class="col-md-12">' +
                                    '<p id="call_notes">' + d[i].call_notes + '</p>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                                $('.calls').prepend(html);


                            }
                            $('.callNotesBtn').clickToggle(function() {
                                $(this).parent().parent().parent('.callContainer').css({
                                    position: 'absolute',
                                    height: '220px',
                                    zIndex: '1020',
                                    left: '8px'
                                });
                            }, function() {
                                $(this).parent().parent().parent('.callContainer').css({
                                    height: '80px'
                                });
                                var e = $(this).parent().parent().parent('.callContainer');
                                setTimeout(function() {
                                    e.css({
                                        position: 'static',

                                    });
                                }, 500);
                            });

                        }
                    }

                });

            }

        }

        this.renderAccused = function() {
            $('.accusedBoxContainer').remove();
            $.ajax({
                async: false,
                type: "get",
                url: "src/dataHandler.php?q=getAccused&client_id=" + $('#client_id').val(),
                success: function(data) {

                    var d = JSON.parse(data);

                    for (var i = 0; i < d.length; i++) {


                        var html = '<form id="accusedUpdateForm_' + d[i].accused_id + '"><div class="accusedBoxContainer col-md-4">' +
                            '<div class="accusedBoxMainLoading">' +
                            '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>' +
                            '<span class="sr-only">Loading...</span>' +
                            '</div>' +
                            '<button class="accusedBoxCloseBtn" type="button">X</button>' +
                            '<input class="aid" name="accused_id" type="hidden" value="' + d[i].accused_id + '">' +

                            '<div class="row accname">' +
                            '<div class="col-md-6"><input type="text" value="' + d[i].accused_surname + '" name="accused_name"></div>' +
                            '<div class="col-md-6"><input type="text" value="' + d[i].accused_name + '" name="accused_surname"></div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-md-6"><p>Accuse Id:</p></div>' +
                            '<div class="col-md-6">' +
                            '<p style="text-decoration: underline;">' + d[i].accused_id + '</p>' +
                            '</div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-md-6"><p>Gender</p></div>' +
                            '<div class="col-md-6">' +
                            '<select name="accused_gender">';
                        if (d[i].accused_gender == 'Male') {
                            html = html + '<option selected value="Male">Male</option>' +
                                '<option value="Female">Fem</option>' +
                                '<option value="Transgender">Trans</option>';

                        } else if (d[i].accused_gender == 'Female') {
                            html = html + '<option value="Male">Male</option>' +
                                '<option selected value="Female">Fem</option>' +
                                '<option value="Transgender">Trans</option>';

                        } else if (d[i].accused_gender == 'Transgender') {
                            html = html + '<option value="Male">Male</option>' +
                                '<option value="Female">Fem</option>' +
                                '<option selected value="Transgender">Trans</option>';

                        }
                        html = html + '</select>' +
                            '</div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-md-6"><p>D.O.B</p></div>' +
                            '<div class="col-md-6">' +
                            '<input type="date" value="' + d[i].accused_dob + '" name="accused_dob" >' +
                            '</div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-md-6"><p>Indegenous</p></div>' +
                            '<div class="col-md-6">';
                        if (d[i].accused_indigenous == 1) {
                            html = html + '<input type="checkbox" checked value="0" name="accused_indigenous" />';
                        } else {
                            html = html + '<input type="checkbox" value="0" name="accused_indigenous" />';
                        }
                        html = html + '</div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-md-6"><p>Young Offender</p></div>' +
                            '<div class="col-md-6">';
                        if (d[i].accused_young_offender == 1) {
                            html = html + '<input type="checkbox" checked value="0" name="accused_young_offender" />';
                        } else {
                            html = html + '<input type="checkbox" value="0" name="accused_young_offender" />';
                        }
                        html = html + '</div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-md-6"><p>Order 516</p></div>' +
                            '<div class="col-md-6 accussedOrder516">';
                        if (d[i].accused_516 == 1) {
                            html = html + '<input type="checkbox" value="0" checked id="accussedOrder516" name="accused_516" />' +
                                '<label for="accussedOrder516"></label>';
                        } else {
                            html = html + '<input type="checkbox" value="0" id="accussedOrder516" name="accused_516" />' +
                                '<label for="accussedOrder516"></label>';
                        }

                        html = html + '</div>' +
                            '</div>' +

                            '<div style="position:relative; width: 100%;"><button data-aid="' + d[i].accused_id + '" type="button" class="accusedModalUpdateBtn">Update</button></div>' +
                            '</div>' +


                            '</div></form>';

                        $('.accusedMainContainer').append(html);
                        if (d[i].accused_indigenous == 1) {
                            $("accusedUpdateForm_" + d[i].accused_id + " input[name='accused_indigenous']").prop('checked', true);
                        }
                        if (d[i].accused_young_offender == 1) {
                            $("accusedUpdateForm_" + d[i].accused_id + " input[name='accused_young_offender']").prop('checked', true);
                        }
                        if (d[i].accused_516 == 1) {
                            $("accusedUpdateForm_" + d[i].accused_id + " input[name='accused_516']").prop('checked', true);
                        }


                    }


                }
            });


        }
        this.getNumOfRecords = function() {
            var obj = this;
            $.ajax({
                type: "get",
                url: "src/dataHandler.php?q=rows",
                async: false,
                success: function(data) {
                    obj.total = data;
                    $('.main-loading').fadeOut();
                }
            });

        }
    }
    //---------------------END ACTION CLASS---------------------------  >>