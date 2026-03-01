import {Controller} from '@hotwired/stimulus';
import {average} from 'color.js'
import 'jquery'
import Chart from 'chart.js/auto';
import {getRelativePosition} from 'chart.js/helpers';
const Swal = require('sweetalert2/dist/sweetalert2.js');

require('../js/base');
require('../js/plugins/ajax_link');
require('../js/plugins/rating');
require('../js/plugins/copy_to_clipboard');

export default class extends Controller {
    static targets = ['img', 'background', 'info']
    ragna = null;

    connect() {
        if(this.img !== undefined) {
            average(this.imgTarget.src, {amount: 1}).then(color => {
                $("#main").attr('style', " background: radial-gradient(100% 100% at 0% 0%, rgba(" + color[0] + ", " + color[1] + ", " + color[2] + ", 0.7) 0%, rgba(0, 0, 0, 0) 100%), #2B2B2B;");

                //$("body").attr('style', " background: radial-gradient(100% 100% at 0% 0%, rgba(" + color[0] + ", " + color[1] + ", " + color[2] + ", 0.2) 0%, rgba(0, 0, 0, 0) 100%), #2B2B2B;");
            });
        }

        $("#delete-account").on('click', function (e) {
            const username = $(this).data('username');
            const deleteUrl = $(this).data('delete-url');

            e.preventDefault();
            Swal.fire({
                title: 'Delete Account',
                html: 'Please enter your username to confirm<br><br><small class="small"><span style="color: #982525;">⚠️ You will receive an email with a link to finalize the account deletion.</span></small>',
                input: 'text',
                inputPlaceholder: username,
                showCancelButton: true,
                confirmButtonText: 'Send Email',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ff0000',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to enter your username';
                    }

                    if (value !== username) {
                        return 'Username does not match';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });

        })

        $("#utilisateur_usernameColor").on('input', function () {
            $(".username span").css({"color": $(this).val()});
        });
        // $("#utilisateur_usernameColor").on('change', function () {
        //     let form = $(".username").closest('form');
        //     let formData = form.serialize();
        //
        //     $.ajax({
        //         type: "POST",
        //         url: form.attr('action'),
        //         data: formData
        //     });
        // });
        // $("form[name=\"utilisateur\"] input,form[name=\"utilisateur\"] select,form[name=\"utilisateur\"] textarea").on('change', function () {
        //     let form = $(this).closest('form');
        //     let formData = form.serialize();
        //     $.ajax({
        //         type: "POST",
        //         url: form.attr('action'),
        //         data: formData
        //     });
        // });
        $(".reset-api-key").on('click', function () {
            if (confirm('You are going to change your api key, are you sure to continue ? ')) {
                $.ajax({
                    type: "POST",
                    url: '/reset/apikey',
                    dataType: 'json',
                    success: function (data) {
                        $("#ApiKey").val(data.value);
                    }
                });
            }

        });

        const chart = new Chart(document.getElementById('UserChart'), {
            type: 'line',
            options: {
                scales: {
                    y: {
                        // stacked: true,
                        beginAtZero: true

                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        // grid line settings
                        grid: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    },
                },
                plugins: {
                    filler: {
                        propagate: false
                    },
                    'samples-filler-analyser': {
                        target: 'chart-analyser'
                    }
                },
                interaction: {
                    intersect: false,
                },
            },
            dataset: null
        });


        $("[data-toggle='tooltip'],[title]").tooltip();
        $(".more-stat").on('click', function () {
            $.ajax({
                url: '/user/more-stats',
                data: {
                    diff: $(this).data('song-difficulty')
                },
                success: function (response) {
                    chart.data.labels = [];
                    chart.data.datasets = [];
                    for (var i = 1; response.dataset[0].data.length >= i; i++) {
                        chart.data.labels.push("Session " + i);
                    }
                    chart.data.datasets = response.dataset;
                    chart.update();
                }
            })
        });

        const config = {
            type: 'scatter',
            data: null,
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return "Drum "+tooltipItem.raw.drum+" : "+tooltipItem.raw.y+"ms";
                            }
                        }
                    },
                },
                xAxis: {
                    key: 'x'
                },
                yAxis: {
                    key: 'y'
                },
                scales: {
                    y: {
                        suggestedMin: -100,
                        suggestedMax: 100
                    },

                    x: {
                        type: 'linear',
                        position: 'bottom'
                    }
                }
            }
        };
        var canvas = $('#scatter-plot');
        canvas.parent().append("<canvas id='scatter-plot'></canvas>");
        canvas.remove();
        const ctx = $('#scatter-plot');
        const chart2 = new Chart(ctx, config);
        $('.scatter-open-score').on('click', function () {
            $.ajax({
                url: '/stats/scatter-score/' + $(this).data('score'),
                dataType: 'json',
                success: function (response) {
                    chart2.data.datasets = [];
                    chart2.data = response.datasets;
                    chart2.update();
                }
            });
        })
        $('.scatter-open-score-history').on('click', function () {
            $("#ScatterView .modal-header .modal-title").html($(this).data('title'));
            $.ajax({
                url: '/stats/scatter-score-history/' + $(this).data('score'),
                dataType: 'json',
                success: function (response) {
                    chart2.data.datasets = [];
                    chart2.data = response.datasets;
                    chart2.update();
                }
            })
        });
        $('.circle').each(function(){
            let items =$(this).find('.menuItem');

            for(let i = 0, l = items.length; i < l; i++) {
                items[i].style.left = (50 - 35*Math.cos(-0.5 * Math.PI - 2*(1/l)*i*Math.PI)).toFixed(4) + "%";

                items[i].style.top = (50 + 35*Math.sin(-0.5 * Math.PI - 2*(1/l)*i*Math.PI)).toFixed(4) + "%";
            }
        })
    }

    disconnect() {
        $("#main").attr('style', " background: transparent");
    }

    back() {
        // history.back();// Swup instance
        //  return false;
    }
}
