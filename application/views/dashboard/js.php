<!-- START: Page CSS-->
<link rel="stylesheet"  href="<?=$base_url?>assets/pick/dist/vendors/chartjs/Chart.min.css">
<link href="<?=$base_url?>assets/pick/dist/vendors/lineprogressbar/jquery.lineProgressbar.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/ionicons/css/ionicons.min.css"> 
<!-- END: Page CSS-->

<!-- START: Page CSS-->   
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/morris/morris.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/weather-icons/css/pe-icon-set-weather.min.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/chartjs/Chart.min.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/starrr/starrr.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/ionicons/css/ionicons.min.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.css">
<!-- END: Page CSS-->

 <!-- START: APP JS-->
 <!-- <script src="<?=$base_url?>assets/pick/dist/js/app.js"></script> -->
<!-- END: APP JS-->

<!-- START: Page Vendor JS-->
<script src="<?=$base_url?>assets/pick/dist/vendors/raphael/raphael.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/morris/morris.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/chartjs/Chart.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/starrr/starrr.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.canvaswrapper.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.colorhelpers.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.saturated.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.browser.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.drawSeries.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.uiConstants.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.legend.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.pie.js"></script>        
<script src="<?=$base_url?>assets/pick/dist/vendors/chartjs/Chart.min.js"></script>  
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-world-mill.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-de-merc.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-us-aea.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/apexcharts/apexcharts.min.js"></script>
<script  src="<?=$base_url?>assets/pick/dist/vendors/lineprogressbar/jquery.lineProgressbar.js"></script>
<script  src="<?=$base_url?>assets/pick/dist/vendors/lineprogressbar/jquery.barfiller.js"></script>
<script  src="<?=$base_url?>assets/js/jquery.blockUI.js"></script>
<!-- END: Page Vendor JS-->

<!-- START: Page JS-->
<!-- <script src="<?=$base_url?>assets/pick/dist/js/home.script.js"></script> -->
<!-- END: Page JS-->

<script>
$(document).ready(function() {
   
    $('#apex_analytic_chart').block({ 
        message: '<h3>please wait...</h3>' 
    }); 
    $('#summary').block({ 
        message: '<h3>please wait...</h3>'
    }); 

    $.ajax({
        type: "GET",
        url: "<?=$base_url?>dashboard/data/getdata_summary",
        dataType: 'json',
        success: function(data)
        {
            $('#all').text(data.all);
            $('#conventional').text(data.conventional);
            $('#digital').text(data.digital);
            $('#cust_repeatcall').text(data.cust_repeatcall);
            $('#summary').unblock();
        }
    });

    $.ajax({
        type: "GET",
        url: "<?=$base_url?>dashboard/data/getdata_grafik",
        dataType: 'json',
        success: function(data)
        {
            /* $('#limit').text(data.limit);
            $('#total_sms').text(data.total_sms);
            $('#total_sms_received').text(data.total_sms_received);
            $('#total_sms_sending').text(data.total_sms_sending);
            $('#total_sms_failed').text(data.total_sms_failed);
            $('#limit_persent').data('percentage',data.limit_persent);
            $('#sms_otomatis').text(data.sms_otomatis);
            $('#contacts').text(data.contacts);
            $('#y_telkomsel').text(data.y_telkomsel);
            $('#y_indosat').text(data.y_indosat);
            $('#y_xl').text(data.y_xl);
            $('#y_axis').text(data.y_axis);
            $('#y_smartfren').text(data.y_smartfren);
            $('#y_three').text(data.y_three);
            $('#y_other').text(data.y_other); */

            kodepos = data.kodepos;
            kec_kel = data.kec_kel; 
            notelp = data.notelp;
            nama_alamat = data.nama_alamat; 

            ////////// Apex Analytic Chart //////////////
            var theme = 'light';
            if ($("#apex_analytic_chart").length > 0)
            {
                options = {
                    legend: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        fontSize: '10px',
                        markers: {
                            radius: 12,
                        },
                    },
                    theme: {
                        mode: theme
                    },
                    chart: {
                        height: 350,
                        type: 'bar',
                    },
                    responsive: [
                        {
                            breakpoint: 767,
                            options: {
                                chart: {
                                    height: 220
                                }
                            }
                        }
                    ],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            colors: ['black']
                        },
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    colors: ['#1ee0ac', '#7782bd', '#753c94', '#f64e60', '#eb6431'],
                    series: [{
                            name: 'Pencarian Kode Pos',
                            data: kodepos
                        }, {
                            name: 'Pencarian Kecamatan Kelurahan',
                            data: kec_kel
                        }, {
                            name: 'Pencarian No Telp',
                            data: notelp
                        }, {
                            name: 'Pencarian Nama / Alamat',
                            data: nama_alamat
                        }],
                    xaxis: {
                        categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
                    },
                    yaxis: {
                        title: {
                            text: '(Call IVR 108 Mothly)'
                        }
                    },
                    fill: {
                        opacity: 1

                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " call"
                            }
                        }
                    }
                }

                var chart = new ApexCharts(
                        document.querySelector("#apex_analytic_chart"),
                        options
                        );
                chart.render();
            }
            $('#apex_analytic_chart').unblock();
        }
    });

    $('.menu').removeClass('active');
    $('#<?=$this->uri->segment(1)?>').addClass('active');
    $('#<?=$this->uri->segment(1)?>').parent().parent().parent('.has-treeview').addClass('menu-open');
    $('.datetime').datepicker({ dateFormat: 'yy-mm-dd' }).prop('autocomplete',"off"); 

    ////////// Bar Filler //////////////
    if ($('.barfiller').length > 0)
    {
        $(".barfiller").each(function () {
            $(this).barfiller({barColor: $(this).data('color')});
            $(this).find('.rounded').css('background',$(this).data('color'));
        });
    }

});

</script>