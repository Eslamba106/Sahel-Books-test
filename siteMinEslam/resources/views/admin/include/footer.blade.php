<footer class="main-footer">
    <div class="pull-right d-none d-sm-inline-block">

        <?php if (!is_admin()) : ?>
        <?php if (check_payment_status() == TRUE || settings()->enable_paypal == 0 || user()->user_type == 'trial') : ?>
        <div id="floating-container">
            <div class="circle1 circle-blue1"></div>
            <div class="floating-menus" style="display:none;">
                <div>
                    <a href="<?php echo url('admin/invoice/create'); ?>"> <?php echo helper_trans('create-new-invoice'); ?>
                        <i class="fa fa-file-text-o"></i></a>
                </div>
                <div>
                    <a href="<?php echo url('admin/estimate/create'); ?>"> <?php echo helper_trans('create-new-estimate'); ?>
                        <i class="fa fa-file-text"></i></a>
                </div>
                <div>
                    <a href="<?php echo url('admin/bills/create'); ?>"><?php echo helper_trans('create-new-bill'); ?>
                        <i class="fa fa-file-text-o"></i></a>
                </div>
                <div>
                    <a href="<?php echo url('admin/customer'); ?>"><?php echo helper_trans('add-customer'); ?>
                        <i class="fa fa-user-o"></i></a>
                </div>
                <div>
                    <a href="<?php echo url('admin/product'); ?>"><?php echo helper_trans('add-product'); ?>
                        <i class="fa fa-list"></i></a>
                </div>
                <div>
                    <a href="<?php echo url('admin/vendor'); ?>"><?php echo helper_trans('add-vendor'); ?>
                        <i class="fa ti-user"></i></a>
                </div>
            </div>
            <div class="fab-button">
                <i class="ti-plus" aria-hidden="true"></i>
            </div>
        </div>
        <?php endif ?>
        <?php endif ?>

    </div>

</footer>

@include('admin.include.js_msg_list')
{{-- @include('name') --}}
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


<?php $success = session('msg'); ?>
<?php $error = session('error'); ?>
<input type="hidden" id="success" value="<?php echo html_escape($success); ?>">
<input type="hidden" id="error" value="<?php echo html_escape($error); ?>">
<input type="hidden" id="base_url" value="<?php echo url(''); ?>/">

<!-- jQuery 3 -->
<script src="{{asset('admin/js/jquery3.min.js')}}"></script>
<!-- popper -->
<script src="{{asset('assets/admin/js/popper.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('assets/admin/js/bootstrap.min.js')}}"></script>
<!-- Custom js -->
<script src="{{asset('admin/js/admin.js')}}"></script>
<script src="{{asset('assets/admin/js/toast.js')}}"></script>
<script src="{{asset('assets/admin/js/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{asset('assets/admin/js/sweet-alert.min.js')}}"></script>
<!-- Datatables-->
<script src="{{asset('assets/admin/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/admin/js/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('assets/admin/js/validation.js')}}"></script>

<script src="{{asset('assets/admin/js/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/ckeditor/ckeditor.js')}}"></script>

<script src="{{asset('assets/admin/js/fastclick.js')}}"></script>

<script src="{{asset('assets/admin/js/template.js')}}"></script>
<script src="{{asset('assets/admin/js/bootstrap-datepicker.min.js')}}"></script>

<script src="{{asset('assets/admin/js/demo.js')}}"></script>
<script src="{{asset('assets/admin/js/select2.min.js')}}"></script>
<script src="{{asset('admin/js/jquery.invoice.js')}}"></script>
<script src="{{asset('assets/admin/js/wow.min.js')}}"></script>

<!-- datatable export buttons -->
<script src="{{asset('assets/admin/js/export_buttons/buttons.min.js')}}"></script>
<script src="{{asset('assets/admin/js/export_buttons/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/admin/js/export_buttons/jszip.min.js')}}"></script>
<script src="{{asset('assets/admin/js/export_buttons/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/admin/js/export_buttons/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/admin/js/export_buttons/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/admin/js/export_buttons/buttons.print.min.js')}}"></script>

<script src="{{asset('assets/admin/js/bootstrap4-toggle.min.js')}}"></script>
<script src="{{asset('assets/admin/js/summernote.js')}}"></script>

@include('include.stripe-js')
<script>
    function get_Total_Function() {
        let discount = parseFloat(document.getElementById("Discount").value);
        let tax = parseFloat(document.getElementById("tax").value);
        let Quantity = parseFloat(document.getElementById("Quantity").value);
        let price = parseFloat(document.getElementById('price').value);
        let total_price = Quantity * price
        let discount_value = parseFloat(discount * total_price);
        let tax_value = parseFloat(tax * total_price);
        document.getElementById('Total_Price').value = [( total_price + tax_value - discount_value  )];
    }
</script>
<script>
    $(document).on('change', "#country", function () {
        var Id = $(this).val();
        if (Id) {
        $.ajax({
            url: "{{ URL::to( 'admin/customer/load_currency') }}/" + Id,
            type: "GET",
            dataType: "json",
            success: function(data){
                $('select[name="currency"]').empty();
                $.each(data , function(key , value){
                    $('select[name= "currency"]').append('<option value="' +value + '">' + value + '</option>')
                });
            },
        });
        }
    });
</script>
{{-- <script>
    $(document).on('change', "#Discount", function () {
        let Discount = $(this).val();
        let tax = parseFloat(document.getElementById("tax").value);
        let price = parseFloat(document.getElementById('price').value);
        console.log(Discount);
        console.log(tax);
        console.log(price);
        if (Discount) {
        $.ajax({
            url: "{{ URL::to( 'admin/invoice/get_total_price') }}/" + Discount + "/" + tax + "/" + price,
            type: "GET",
            dataType: "float",
            success: function(data){
                // $('select[name="total"]').empty();
                $.each(data , function(value){
                    $('select[name= "total_price[]"]').append(value)
                });
            },
        });
        }
    });
</script> --}}
<!-- datatable export buttons -->
<script type="text/javascript">
    pdfMake.fonts = {
        Roboto: {
            normal: 'Roboto-Regular.ttf',
            bold: 'Roboto-Regular.ttf',
            italics: 'Roboto-Regular.ttf',
            bolditalics: 'Roboto-Regular.ttf'
        },
        Amiri: {
            normal: 'Amiri-Regular.ttf',
            bold: 'Amiri-Regular.ttf',
            italics: 'Amiri-Regular.ttf',
            bolditalics: 'Amiri-Regular.ttf'
        }
    };


    $(document).ready(function() {

        $('#summernote').summernote();



        /*  pdfMake.fonts = {
        Arial: {
                normal: 'arial.ttf',
                bold: 'arialbd.ttf',
                italics: 'ariali.ttf',
                bolditalics: 'arialbi.ttf'
        }
};*/
        /*pdfMake.fonts = {
            // Default font should still be available
            Roboto: {
                normal: 'Roboto-Regular.ttf',
                bold: 'Roboto-Medium.ttf',
                italics: 'Roboto-Italic.ttf',
                bolditalics: 'Roboto-Italic.ttf'
            },
            // Make sure you define all 4 components - normal, bold, italics, bolditalics - (even if they all point to the same font file)
            Alef: {
                normal: 'Alef.ttf',
                bold: 'Alef.ttf',
                italics: 'Alef.ttf',
                bolditalics: 'Alef.ttf'
            }
            
        }*/
        /*window.pdfMake.fonts = {
                alef: {
                    normal: '{{ asset('assets//admin/fonts/Alef-Bold.ttf',
                    bold: '{{ asset('assets//admin/fonts/Alef-Bold.ttf',
                    italics: '{{ asset('assets/admin/fonts/Alef-Bold.ttf',//'asAlef-Bold.ttf"',
                    bolditalics: '{{ asset('assets//admin/fonts/Alef-Bold.ttf',
                }
            };
            pdfMake.createPdf( pdfMake.docDefinition, null,  pdfMake.fonts);*/

        $('.dt_btn').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy',
                {
                    extend: 'csv',
                    charset: 'UTF-8',
                    bom: true
                },
                'excel', {
                    extend: 'pdf',
                    charset: 'UTF-8',
                    bom: true,
                    customize: function(doc) {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                            .length + 1).join('*').split('');
                        //doc.styles.tableFooter.alignment = 'right';
                        //doc.styles.tableHeader.alignment = 'right';
                        doc.styles.tableBodyEven.alignment = 'right';
                        doc.styles.tableBodyOdd.alignment = 'right';
                        doc.defaultStyle.font = 'Amiri';
                    }

                }, 'print'
            ]
        });
    });
</script>
<script>


        // $('#invoice_number').hide();

        $('select[name="eslam"]').on('change' ,function() {
            let name_opt = $(this).find('option:selected').text();
            if(name_opt =='with'){
                $('#tax_input').show();
            }else{
                $('#tax_input').hide();
            }
        });
</script>
<script>
    $('select[name="badawyy"]').on('change' ,function() {
        let esss = $(this).find('option:selected').text();
        console.log('dgsh');
    });
</script>
<!-- high charts js-->
<script src="https://code.highcharts.com/highcharts.js"></script>

<script>
    <?php if (isset($page_title) && $page_title == 'User Dashboard') : ?>


    var incomeData = <?php echo $income_data; ?>;
    var expenseData = <?php echo $expense_data; ?>;
    var incomeAxis = <?php echo $income_axis; ?>;

    Highcharts.chart('incomeChart', {
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: ''
        },
        xAxis: {
            reversed: true,
            categories: incomeAxis
        },
        yAxis: {
            title: {
                text: ''
            }

        },
        legend: {
            enabled: true
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '<?php echo isset($currency) ? html_escape($currency) : ''; ?> {point.y}'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span> <b><?php echo isset($currency) ? html_escape($currency) : ''; ?> {point.y}</b><br/>'
        },

        series: [{
                name: "<?php echo helper_trans('income'); ?>",
                data: incomeData,
                color: '#2568ef'
            },
            {
                name: "<?php echo helper_trans('expense'); ?>",
                data: expenseData,
                color: '#67757c'
            }
        ]
    });

    <?php endif ?>

    <?php if (isset($page_title) && $page_title == 'Dashboard') : ?>

    var incomeData = <?php echo $income_data; ?>;
    var incomeAxis = <?php echo $income_axis; ?>;

    Highcharts.chart('adminIncomeChart', {
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: ''
        },
        xAxis: {
            reversed: true,
            categories: incomeAxis
        },
        yAxis: {
            title: {
                text: ''
            }

        },
        legend: {
            enabled: true
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '<?php echo html_escape($currency); ?>{point.y}'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span> <b><?php echo html_escape($currency); ?>{point.y}</b><br/>'
        },

        series: [{
            name: "<?php echo helper_trans('income'); ?>",
            data: incomeData,
            color: '#2568ef'
        }]
    });


    //users packages share pie chart

    Highcharts.chart('packagePie', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: ''
        },
        credits: {
            enabled: false
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: 'Users',
            colorByPoint: true,

            data: [
                <?php
                foreach ($upackages as $upackage) {
                    echo '{
                                                                  name: "' .
                        $upackage->name .
                        '",
                                                                  y: ' .
                        $upackage->total .
                        '
                                                                },';
                }
                ?>
            ]
        }]
    });

    <?php endif ?>
</script>
<!-- high charts js end-->

<script src="{{ asset('assets/admin/js/printThis.js')}}"></script>
<!-- Color Picker Plugin JavaScript -->
<script src="{{ asset('assets/admin/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}">
</script>


<!-- bt-switch -->
<script src="{{ asset('assets/admin/js/bootstrap-switch.min.js')}}"></script>
<script type="text/javascript">
    $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
    var radioswitch = function() {
        var bt = function() {
            $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioState")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
            })
        };
        return {
            init: function() {
                bt()
            }
        }
    }();
    $(document).ready(function() {
        radioswitch.init()
    });
</script>


<!-- Style switcher -->
<script src="{{ asset('assets/admin/js/jQuery.style.switcher.js')}}"></script>

<script type="text/javascript">
    <?php if (isset($success)) : ?>
    $(document).ready(function() {
        var msg = $('#success').val();
        var msg_success = $('.msg_success').val();

        $.toast({
            heading: msg_success,
            text: msg,
            position: 'top-right',
            loaderBg: '#fff',
            icon: 'success',
            hideAfter: 8000
        });

    });
    <?php endif; ?>


    <?php if (isset($error)) : ?>
    $(document).ready(function() {
        var msg = $('#error').val();
        var msg_error = $('.msg_error').val();

        $.toast({
            heading: msg_error,
            text: msg,
            position: 'top-right',
            loaderBg: '#fff',
            icon: 'error',
            hideAfter: 8000
        });

    });
    <?php endif; ?>
</script>

<script>
    ! function(window, document, $) {
        "use strict";
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    }(window, document, jQuery);

    $(document).ready(function() {
        $('.datatable').dataTable();
        $('.multiple_select').select2();
        $('.single_select').select2();
    });
</script>

<script type="text/javascript">
    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });

    //colorpicker start
    $('.colorpicker-default').colorpicker({
        format: 'hex'
    });
    $('.colorpicker-rgba').colorpicker();
</script>

<!-- <script>
    CKEDITOR.replace('ckEditor', {
        language: 'en',
        filebrowserImageUploadUrl: "<?php //echo url('');
        ?>admin/post/upload_ckimage_post?key=kgh764hdj990sghsg46r"
    });
</script> -->

<?php if (isset($page_sub) && $page_sub == 'Edit') : ?>
<script type="text/javascript">
    $(document).ready(function() {
        var Id = $('#customer').val();
        var base_url = $('#base_url').val();
        if (Id != '') {
            var url = base_url + 'admin/customer/load_customer_info/' + Id;
            $.post(url, {
                data: 'value',
                'csrf_test_name': csrf_token
            }, function(json) {
                if (json.st == 1) {
                    $('#load_info').html(json.value);
                    $('.currency_wrapper').html(json.currency);
                    $('.currency_name').html(json.currency_name);
                    $('.currency_code').val(json.code);
                }
            }, 'json');
        } else {
            $('.currency_wrapper').html('');
            $('#load_info').html('Select a customer');
        }
    });
</script>
<?php endif ?>


<?php if (isset($page_sub) && $page_sub == 'Edit Bill') : ?>
<script type="text/javascript">
    $(document).ready(function() {
        var Id = $('#vendors').val();
        var base_url = $('#base_url').val();
        if (Id != '') {
            var url = base_url + 'admin/vendor/load_customer_info/' + Id;
            $.post(url, {
                data: 'value',
                'csrf_test_name': csrf_token
            }, function(json) {
                if (json.st == 1) {
                    $('#load_info').html(json.value);
                    $('.currency_wrapper').html(json.currency);
                    $('.currency_name').html(json.currency_name);
                    $('.currency_code').val(json.code);
                }
            }, 'json');
        } else {
            $('.currency_wrapper').html('');
            $('#load_info').html('Select a vendor');
        }
    });
</script>
<?php endif ?>


<?php if (isset($page) && $page == 'Invoice' || isset($page) && $page == 'Create' || isset($page) && $page == 'Bill') : ?>
<script type="text/javascript">
    $(document).on("click", function() {
        var base_url = $('#base_url').val();
        var total = $('.grandtotal').val();
        var code = $('.currency_code').val();

        var url = base_url + 'admin/invoice/convert_currency/' + total + '/' + code;
        $.post(url, {
            data: 'value',
            'csrf_test_name': csrf_token
        }, function(json) {
            if (json.st == 1) {
                $('.conversion_currency').html(json.result);
                $('.convert_total').val(json.convert_total);
            }
        }, 'json');
    });
</script>
<?php endif ?>

</body>

</html>
