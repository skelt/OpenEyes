<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>

<div class="d3">
    <svg id="pcr-risk-chart" class="chart" style="width: 600px; height: 400px;"></svg>
</div>
<script type="text/javascript">

    // d3.legend.js
    // (C) 2012 ziggy.jonsson.nyc@gmail.com
    // MIT licence

    (function () {
        d3.legend = function (g) {
            g.each(function () {
                var g = d3.select(this),
                    items = {},
                    svg = d3.select(g.property("nearestViewportElement")),
                    li = g.selectAll(".legend-items").data([true]);

                //lb.enter().append("rect").classed("legend-box",true)
                li.enter().append("g").classed("legend-items", true);

                svg.selectAll("[data-legend]").each(function () {
                    var self = d3.select(this);
                    items[self.attr("data-legend")] = {
                        pos: self.attr("data-legend-pos") || this.getBBox().y,
                        color: self.attr("data-legend-color") != undefined ? self.attr("data-legend-color") : self.style("fill") != 'none' ? self.style("fill") : self.style("stroke")
                    }
                });

                items = d3.entries(items).sort(function (a, b) {
                    return a.value.pos - b.value.pos
                });

                li.selectAll("text")
                    .data(items, function (d) {
                        return d.key
                    })
                    .call(function (d) {
                        d.enter().append("text")
                    })
                    .call(function (d) {
                        d.exit().remove()
                    })
                    .attr("y", function (d, i) {
                        return (i * 1.5) + "em"
                    })
                    .attr("x", "1em")
                    .text(function (d) {
                        return d.key
                    });

                li.selectAll("circle")
                    .data(items, function (d) {
                        return d.key
                    })
                    .call(function (d) {
                        d.enter().append("circle")
                    })
                    .call(function (d) {
                        d.exit().remove()
                    })
                    .attr("cy", function (d, i) {
                        return (i * 1.5) - 0.3 + "em"
                    })
                    .attr("cx", 0)
                    .attr("r", "0.4em")
                    .style("fill", function (d) {
                        return d.value.color
                    });

            });
            return g
        }
    })();
    //delete and calculate
    var upper98 = [
        {
            "x": 10,
            "y": 95.88718066
        },
        {
            "x": 15,
            "y": 86.43966623
        },
        {
            "x": 20,
            "y": 74.63561146
        },
        {
            "x": 25,
            "y": 63.45375058
        },
        {
            "x": 30,
            "y": 54.04910679
        },
        {
            "x": 35,
            "y": 46.49744906
        },
        {
            "x": 40,
            "y": 40.50920596
        },
        {
            "x": 45,
            "y": 35.74685611
        },
        {
            "x": 50,
            "y": 31.92287045
        },
        {
            "x": 55,
            "y": 28.814874
        },
        {
            "x": 60,
            "y": 26.25657891
        },
        {
            "x": 65,
            "y": 24.12468519
        },
        {
            "x": 70,
            "y": 22.32749924
        },
        {
            "x": 75,
            "y": 20.79626746
        },
        {
            "x": 80,
            "y": 19.47888652
        },
        {
            "x": 85,
            "y": 18.33541407
        },
        {
            "x": 90,
            "y": 17.33487259
        },
        {
            "x": 95,
            "y": 16.45296386
        },
        {
            "x": 100,
            "y": 15.67042149
        },
        {
            "x": 105,
            "y": 14.97181192
        },
        {
            "x": 110,
            "y": 14.34465254
        },
        {
            "x": 115,
            "y": 13.77875635
        },
        {
            "x": 120,
            "y": 13.2657396
        },
        {
            "x": 125,
            "y": 12.79864828
        },
        {
            "x": 130,
            "y": 12.37167188
        },
        {
            "x": 135,
            "y": 11.97992204
        },
        {
            "x": 140,
            "y": 11.61925975
        },
        {
            "x": 145,
            "y": 11.28615947
        },
        {
            "x": 150,
            "y": 10.97760127
        },
        {
            "x": 155,
            "y": 10.69098464
        },
        {
            "x": 160,
            "y": 10.42405916
        },
        {
            "x": 165,
            "y": 10.17486825
        },
        {
            "x": 170,
            "y": 9.9417033
        },
        {
            "x": 175,
            "y": 9.72306611
        },
        {
            "x": 180,
            "y": 9.51763776
        },
        {
            "x": 185,
            "y": 9.32425291
        },
        {
            "x": 190,
            "y": 9.14187825
        },
        {
            "x": 195,
            "y": 8.96959454
        },
        {
            "x": 200,
            "y": 8.80658139
        },
        {
            "x": 205,
            "y": 8.65210447
        },
        {
            "x": 210,
            "y": 8.50550459
        },
        {
            "x": 215,
            "y": 8.36618837
        },
        {
            "x": 220,
            "y": 8.23362036
        },
        {
            "x": 225,
            "y": 8.1073161
        },
        {
            "x": 230,
            "y": 7.98683629
        },
        {
            "x": 235,
            "y": 7.87178168
        },
        {
            "x": 240,
            "y": 7.76178857
        },
        {
            "x": 245,
            "y": 7.65652505
        },
        {
            "x": 250,
            "y": 7.55568756
        },
        {
            "x": 255,
            "y": 7.45899794
        },
        {
            "x": 260,
            "y": 7.36620086
        },
        {
            "x": 265,
            "y": 7.27706154
        },
        {
            "x": 270,
            "y": 7.19136372
        },
        {
            "x": 275,
            "y": 7.10890785
        },
        {
            "x": 280,
            "y": 7.02950957
        },
        {
            "x": 285,
            "y": 6.95299823
        },
        {
            "x": 290,
            "y": 6.8792157
        },
        {
            "x": 295,
            "y": 6.80801521
        },
        {
            "x": 300,
            "y": 6.73926036
        },
        {
            "x": 305,
            "y": 6.67282423
        },
        {
            "x": 310,
            "y": 6.60858855
        },
        {
            "x": 315,
            "y": 6.546443
        },
        {
            "x": 320,
            "y": 6.48628455
        },
        {
            "x": 325,
            "y": 6.42801685
        },
        {
            "x": 330,
            "y": 6.37154972
        },
        {
            "x": 335,
            "y": 6.31679865
        },
        {
            "x": 340,
            "y": 6.26368436
        },
        {
            "x": 345,
            "y": 6.21213243
        },
        {
            "x": 350,
            "y": 6.16207289
        },
        {
            "x": 355,
            "y": 6.11343992
        },
        {
            "x": 360,
            "y": 6.06617154
        },
        {
            "x": 365,
            "y": 6.02020933
        },
        {
            "x": 370,
            "y": 5.9754982
        },
        {
            "x": 375,
            "y": 5.93198611
        },
        {
            "x": 380,
            "y": 5.88962392
        },
        {
            "x": 385,
            "y": 5.84836512
        },
        {
            "x": 390,
            "y": 5.80816574
        },
        {
            "x": 395,
            "y": 5.7689841
        },
        {
            "x": 400,
            "y": 5.73078072
        },
        {
            "x": 405,
            "y": 5.69351815
        },
        {
            "x": 410,
            "y": 5.65716082
        },
        {
            "x": 415,
            "y": 5.62167499
        },
        {
            "x": 420,
            "y": 5.58702857
        },
        {
            "x": 425,
            "y": 5.55319103
        },
        {
            "x": 430,
            "y": 5.52013333
        },
        {
            "x": 435,
            "y": 5.48782783
        },
        {
            "x": 440,
            "y": 5.45624817
        },
        {
            "x": 445,
            "y": 5.42536924
        },
        {
            "x": 450,
            "y": 5.39516708
        },
        {
            "x": 455,
            "y": 5.36561882
        },
        {
            "x": 460,
            "y": 5.33670263
        },
        {
            "x": 465,
            "y": 5.30839763
        },
        {
            "x": 470,
            "y": 5.2806839
        },
        {
            "x": 475,
            "y": 5.25354234
        },
        {
            "x": 480,
            "y": 5.22695471
        },
        {
            "x": 485,
            "y": 5.20090352
        },
        {
            "x": 490,
            "y": 5.17537204
        },
        {
            "x": 495,
            "y": 5.15034423
        },
        {
            "x": 500,
            "y": 5.12580471
        },
        {
            "x": 505,
            "y": 5.10173872
        },
        {
            "x": 510,
            "y": 5.0781321
        },
        {
            "x": 515,
            "y": 5.05497127
        },
        {
            "x": 520,
            "y": 5.03224318
        },
        {
            "x": 525,
            "y": 5.00993529
        },
        {
            "x": 530,
            "y": 4.98803553
        },
        {
            "x": 535,
            "y": 4.96653233
        },
        {
            "x": 540,
            "y": 4.94541453
        },
        {
            "x": 545,
            "y": 4.9246714
        },
        {
            "x": 550,
            "y": 4.90429261
        },
        {
            "x": 555,
            "y": 4.88426821
        },
        {
            "x": 560,
            "y": 4.86458863
        },
        {
            "x": 565,
            "y": 4.84524461
        },
        {
            "x": 570,
            "y": 4.82622727
        },
        {
            "x": 575,
            "y": 4.80752802
        },
        {
            "x": 580,
            "y": 4.78913856
        },
        {
            "x": 585,
            "y": 4.7710509
        },
        {
            "x": 590,
            "y": 4.75325734
        },
        {
            "x": 595,
            "y": 4.7357504
        },
        {
            "x": 600,
            "y": 4.71852291
        },
        {
            "x": 605,
            "y": 4.70156789
        },
        {
            "x": 610,
            "y": 4.68487863
        },
        {
            "x": 615,
            "y": 4.66844862
        },
        {
            "x": 620,
            "y": 4.65227159
        },
        {
            "x": 625,
            "y": 4.63634144
        },
        {
            "x": 630,
            "y": 4.62065229
        },
        {
            "x": 635,
            "y": 4.60519845
        },
        {
            "x": 640,
            "y": 4.5899744
        },
        {
            "x": 645,
            "y": 4.5749748
        },
        {
            "x": 650,
            "y": 4.56019448
        },
        {
            "x": 655,
            "y": 4.54562842
        },
        {
            "x": 660,
            "y": 4.53127176
        },
        {
            "x": 665,
            "y": 4.51711979
        },
        {
            "x": 670,
            "y": 4.50316795
        },
        {
            "x": 675,
            "y": 4.4894118
        },
        {
            "x": 680,
            "y": 4.47584704
        },
        {
            "x": 685,
            "y": 4.46246951
        },
        {
            "x": 690,
            "y": 4.44927515
        },
        {
            "x": 695,
            "y": 4.43626003
        },
        {
            "x": 700,
            "y": 4.42342034
        },
        {
            "x": 705,
            "y": 4.41075236
        },
        {
            "x": 710,
            "y": 4.3982525
        },
        {
            "x": 715,
            "y": 4.38591726
        },
        {
            "x": 720,
            "y": 4.37374323
        },
        {
            "x": 725,
            "y": 4.36172711
        },
        {
            "x": 730,
            "y": 4.34986568
        },
        {
            "x": 735,
            "y": 4.33815581
        },
        {
            "x": 740,
            "y": 4.32659447
        },
        {
            "x": 745,
            "y": 4.31517869
        },
        {
            "x": 750,
            "y": 4.3039056
        },
        {
            "x": 755,
            "y": 4.29277238
        },
        {
            "x": 760,
            "y": 4.28177632
        },
        {
            "x": 765,
            "y": 4.27091476
        },
        {
            "x": 770,
            "y": 4.26018511
        },
        {
            "x": 775,
            "y": 4.24958484
        },
        {
            "x": 780,
            "y": 4.23911151
        },
        {
            "x": 785,
            "y": 4.22876272
        },
        {
            "x": 790,
            "y": 4.21853615
        },
        {
            "x": 795,
            "y": 4.20842951
        },
        {
            "x": 800,
            "y": 4.1984406
        },
        {
            "x": 805,
            "y": 4.18856726
        },
        {
            "x": 810,
            "y": 4.17880737
        },
        {
            "x": 815,
            "y": 4.16915889
        },
        {
            "x": 820,
            "y": 4.15961982
        },
        {
            "x": 825,
            "y": 4.15018819
        },
        {
            "x": 830,
            "y": 4.1408621
        },
        {
            "x": 835,
            "y": 4.13163968
        },
        {
            "x": 840,
            "y": 4.12251913
        },
        {
            "x": 845,
            "y": 4.11349866
        },
        {
            "x": 850,
            "y": 4.10457655
        },
        {
            "x": 855,
            "y": 4.09575109
        },
        {
            "x": 860,
            "y": 4.08702065
        },
        {
            "x": 865,
            "y": 4.0783836
        },
        {
            "x": 870,
            "y": 4.06983836
        },
        {
            "x": 875,
            "y": 4.06138341
        },
        {
            "x": 880,
            "y": 4.05301722
        },
        {
            "x": 885,
            "y": 4.04473833
        },
        {
            "x": 890,
            "y": 4.0365453
        },
        {
            "x": 895,
            "y": 4.02843672
        },
        {
            "x": 900,
            "y": 4.02041122
        },
        {
            "x": 905,
            "y": 4.01246745
        },
        {
            "x": 910,
            "y": 4.0046041
        },
        {
            "x": 915,
            "y": 3.99681988
        },
        {
            "x": 920,
            "y": 3.98911353
        },
        {
            "x": 925,
            "y": 3.98148381
        },
        {
            "x": 930,
            "y": 3.97392953
        },
        {
            "x": 935,
            "y": 3.9664495
        },
        {
            "x": 940,
            "y": 3.95904257
        },
        {
            "x": 945,
            "y": 3.95170761
        },
        {
            "x": 950,
            "y": 3.94444351
        },
        {
            "x": 955,
            "y": 3.93724918
        },
        {
            "x": 960,
            "y": 3.93012358
        },
        {
            "x": 965,
            "y": 3.92306565
        },
        {
            "x": 970,
            "y": 3.91607438
        },
        {
            "x": 975,
            "y": 3.90914878
        },
        {
            "x": 980,
            "y": 3.90228786
        },
        {
            "x": 985,
            "y": 3.89549066
        },
        {
            "x": 990,
            "y": 3.88875626
        },
        {
            "x": 995,
            "y": 3.88208373
        },
        {
            "x": 1000,
            "y": 3.87547218
        }
    ];
    var upper95 = [{"x": 10, "y": 63.75616972}, {"x": 15, "y": 43.591758}, {"x": 20, "y": 32.12395971}, {
        "x": 25,
        "y": 25.29952064
    }, {"x": 30, "y": 20.92077739}, {"x": 35, "y": 17.92141817}, {"x": 40, "y": 15.75693462}, {
        "x": 45,
        "y": 14.12916247
    }, {"x": 50, "y": 12.86399503}, {"x": 55, "y": 11.85402032}, {"x": 60, "y": 11.0298304}, {
        "x": 65,
        "y": 10.34478712
    }, {"x": 70, "y": 9.7664944}, {"x": 75, "y": 9.27179992}, {"x": 80, "y": 8.84374605}, {
        "x": 85,
        "y": 8.4696437
    }, {"x": 90, "y": 8.13981787}, {"x": 95, "y": 7.84676826}, {"x": 100, "y": 7.58459394}, {
        "x": 105,
        "y": 7.34859057
    }, {"x": 110, "y": 7.13496293}, {"x": 115, "y": 6.94061616}, {"x": 120, "y": 6.7630018}, {
        "x": 125,
        "y": 6.60000253
    }, {"x": 130, "y": 6.4498449}, {"x": 135, "y": 6.31103229}, {"x": 140, "y": 6.18229302}, {
        "x": 145,
        "y": 6.06253958
    }, {"x": 150, "y": 5.95083649}, {"x": 155, "y": 5.8463746}, {"x": 160, "y": 5.74845044}, {
        "x": 165,
        "y": 5.65644952
    }, {"x": 170, "y": 5.56983263}, {"x": 175, "y": 5.48812466}, {"x": 180, "y": 5.41090533}, {
        "x": 185,
        "y": 5.33780146
    }, {"x": 190, "y": 5.26848056}, {"x": 195, "y": 5.20264538}, {"x": 200, "y": 5.14002939}, {
        "x": 205,
        "y": 5.08039285
    }, {"x": 210, "y": 5.02351954}, {"x": 215, "y": 4.96921394}, {"x": 220, "y": 4.91729878}, {
        "x": 225,
        "y": 4.86761295
    }, {"x": 230, "y": 4.8200097}, {"x": 235, "y": 4.77435507}, {"x": 240, "y": 4.7305265}, {
        "x": 245,
        "y": 4.68841166
    }, {"x": 250, "y": 4.64790738}, {"x": 255, "y": 4.60891873}, {"x": 260, "y": 4.57135823}, {
        "x": 265,
        "y": 4.53514508
    }, {"x": 270, "y": 4.5002046}, {"x": 275, "y": 4.46646759}, {"x": 280, "y": 4.43386988}, {
        "x": 285,
        "y": 4.40235186
    }, {"x": 290, "y": 4.37185806}, {"x": 295, "y": 4.34233683}, {"x": 300, "y": 4.31373999}, {
        "x": 305,
        "y": 4.28602256
    }, {"x": 310, "y": 4.25914248}, {"x": 315, "y": 4.23306036}, {"x": 320, "y": 4.20773932}, {
        "x": 325,
        "y": 4.18314474
    }, {"x": 330, "y": 4.15924412}, {"x": 335, "y": 4.1360069}, {"x": 340, "y": 4.11340433}, {
        "x": 345,
        "y": 4.09140932
    }, {"x": 350, "y": 4.06999636}, {"x": 355, "y": 4.04914134}, {"x": 360, "y": 4.02882151}, {
        "x": 365,
        "y": 4.00901537
    }, {"x": 370, "y": 3.98970257}, {"x": 375, "y": 3.97086385}, {"x": 380, "y": 3.95248096}, {
        "x": 385,
        "y": 3.93453659
    }, {"x": 390, "y": 3.91701432}, {"x": 395, "y": 3.89989855}, {"x": 400, "y": 3.88317448}, {
        "x": 405,
        "y": 3.866828
    }, {"x": 410, "y": 3.85084571}, {"x": 415, "y": 3.83521485}, {"x": 420, "y": 3.81992324}, {
        "x": 425,
        "y": 3.8049593
    }, {"x": 430, "y": 3.79031197}, {"x": 435, "y": 3.77597068}, {"x": 440, "y": 3.76192536}, {
        "x": 445,
        "y": 3.74816637
    }, {"x": 450, "y": 3.73468451}, {"x": 455, "y": 3.72147096}, {"x": 460, "y": 3.70851729}, {
        "x": 465,
        "y": 3.69581543
    }, {"x": 470, "y": 3.68335764}, {"x": 475, "y": 3.67113652}, {"x": 480, "y": 3.65914495}, {
        "x": 485,
        "y": 3.64737611
    }, {"x": 490, "y": 3.63582346}, {"x": 495, "y": 3.62448071}, {"x": 500, "y": 3.61334183}, {
        "x": 505,
        "y": 3.60240101
    }, {"x": 510, "y": 3.59165267}, {"x": 515, "y": 3.58109145}, {"x": 520, "y": 3.57071218}, {
        "x": 525,
        "y": 3.5605099
    }, {"x": 530, "y": 3.55047982}, {"x": 535, "y": 3.54061732}, {"x": 540, "y": 3.53091796}, {
        "x": 545,
        "y": 3.52137746
    }, {"x": 550, "y": 3.51199169}, {"x": 555, "y": 3.50275667}, {"x": 560, "y": 3.49366854}, {
        "x": 565,
        "y": 3.4847236
    }, {"x": 570, "y": 3.47591825}, {"x": 575, "y": 3.46724904}, {"x": 580, "y": 3.45871262}, {
        "x": 585,
        "y": 3.45030575
    }, {"x": 590, "y": 3.44202531}, {"x": 595, "y": 3.43386827}, {"x": 600, "y": 3.4258317}, {
        "x": 605,
        "y": 3.41791277
    }, {"x": 610, "y": 3.41010874}, {"x": 615, "y": 3.40241695}, {"x": 620, "y": 3.39483483}, {
        "x": 625,
        "y": 3.3873599
    }, {"x": 630, "y": 3.37998972}, {"x": 635, "y": 3.37272198}, {"x": 640, "y": 3.36555438}, {
        "x": 645,
        "y": 3.35848474
    }, {"x": 650, "y": 3.35151092}, {"x": 655, "y": 3.34463084}, {"x": 660, "y": 3.3378425}, {
        "x": 665,
        "y": 3.33114395
    }, {"x": 670, "y": 3.32453328}, {"x": 675, "y": 3.31800866}, {"x": 680, "y": 3.31156829}, {
        "x": 685,
        "y": 3.30521045
    }, {"x": 690, "y": 3.29893343}, {"x": 695, "y": 3.29273561}, {"x": 700, "y": 3.28661537}, {
        "x": 705,
        "y": 3.28057118
    }, {"x": 710, "y": 3.27460152}, {"x": 715, "y": 3.26870491}, {"x": 720, "y": 3.26287994}, {
        "x": 725,
        "y": 3.25712521
    }, {"x": 730, "y": 3.25143935}, {"x": 735, "y": 3.24582107}, {"x": 740, "y": 3.24026906}, {
        "x": 745,
        "y": 3.23478208
    }, {"x": 750, "y": 3.2293589}, {"x": 755, "y": 3.22399835}, {"x": 760, "y": 3.21869926}, {
        "x": 765,
        "y": 3.21346051
    }, {"x": 770, "y": 3.20828099}, {"x": 775, "y": 3.20315963}, {"x": 780, "y": 3.19809538}, {
        "x": 785,
        "y": 3.19308723
    }, {"x": 790, "y": 3.18813418}, {"x": 795, "y": 3.18323525}, {"x": 800, "y": 3.1783895}, {
        "x": 805,
        "y": 3.173596
    }, {"x": 810, "y": 3.16885385}, {"x": 815, "y": 3.16416217}, {"x": 820, "y": 3.15952009}, {
        "x": 825,
        "y": 3.15492678
    }, {"x": 830, "y": 3.15038141}, {"x": 835, "y": 3.14588317}, {"x": 840, "y": 3.14143129}, {
        "x": 845,
        "y": 3.137025
    }, {"x": 850, "y": 3.13266355}, {"x": 855, "y": 3.1283462}, {"x": 860, "y": 3.12407224}, {
        "x": 865,
        "y": 3.11984097
    }, {"x": 870, "y": 3.11565171}, {"x": 875, "y": 3.11150377}, {"x": 880, "y": 3.10739652}, {
        "x": 885,
        "y": 3.10332931
    }, {"x": 890, "y": 3.0993015}, {"x": 895, "y": 3.09531249}, {"x": 900, "y": 3.09136168}, {
        "x": 905,
        "y": 3.08744848
    }, {"x": 910, "y": 3.08357231}, {"x": 915, "y": 3.07973261}, {"x": 920, "y": 3.07592883}, {
        "x": 925,
        "y": 3.07216042
    }, {"x": 930, "y": 3.06842687}, {"x": 935, "y": 3.06472764}, {"x": 940, "y": 3.06106223}, {
        "x": 945,
        "y": 3.05743015
    }, {"x": 950, "y": 3.05383091}, {"x": 955, "y": 3.05026402}, {"x": 960, "y": 3.04672902}, {
        "x": 965,
        "y": 3.04322546
    }, {"x": 970, "y": 3.03975288}, {"x": 975, "y": 3.03631084}, {"x": 980, "y": 3.0328989}, {
        "x": 985,
        "y": 3.02951666
    }, {"x": 990, "y": 3.02616368}, {"x": 995, "y": 3.02283956}, {"x": 1000, "y": 3.01954389}];
    var surgeon = [{"x": 225, "y": 2.8}];
    var surgeonx = [225];
    var surgeony = [2.8];

    var vis = d3.select("#pcr-risk-chart"),
        WIDTH = 600,
        HEIGHT = 400,
        MARGINS = {
            top: 50,
            right: 20,
            bottom: 50,
            left: 50
        },
        average = [{"x": 0, "y": 1.95}, {"x": 1000, "y": 1.95}],
        xScale = d3.scale.linear().range([MARGINS.left, WIDTH - MARGINS.right]).domain([0, 1000]),
        yScale = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([0, 30]),
        xAxis = d3.svg.axis()
            .scale(xScale),
        yAxis = d3.svg.axis()
            .scale(yScale)
            .orient("left"),
        lineGen = d3.svg.line()
            .x(function (d) {
                return xScale(d.x);
            })
            .y(function (d) {
                return yScale(d.y);
            });

    // add the tooltip area to the webpage
    var tooltip = d3.select("body").append("div")
        .attr("class", "chart-tooltip")
        .style("opacity", 0);

    vis.append("svg:g")
        .attr("class", "axis")
        .attr("transform", "translate(0," + (HEIGHT - MARGINS.bottom) + ")")
        .call(xAxis);

    vis.append("svg:g")
        .attr("class", "axis")
        .attr("transform", "translate(" + (MARGINS.left) + ",0)")
        .call(yAxis);

    vis.append('svg:path')
        .attr('d', lineGen(average))
        .attr('stroke', 'green')
        .attr('stroke-width', 1)
        .style('stroke-dasharray', ("3, 3"))
        .attr("data-legend", "Average Rate 1.95%")
        .attr('fill', 'none');

    vis.append('svg:path')
        .attr('d', lineGen(upper98))
        .attr('stroke', 'red')
        .attr('stroke-width', 1)
        .attr("data-legend", "Upper 99.8%")
        .attr('fill', 'none');

    vis.append('svg:path')
        .attr('d', lineGen(upper95))
        .attr('stroke', 'orange')
        .attr('stroke-width', 1)
        .attr("data-legend", "Upper 95%")
        .attr('fill', 'none');

    vis.selectAll(".point")
        .data(surgeon)  // using the values in the ydata array
        .enter().append("path")  // create a new circle for each value
        .attr("class", "point")
        .attr("d", d3.svg.symbol().type("diamond"))
        .attr("transform", function (d) {
            return "translate(" + xScale(d.x) + "," + yScale(d.y) + ")";
        })
        .attr("data-legend", "Surgeon")
        .on("mouseover", function (d, i) {
            tooltip.transition()
                .duration(200)
                .style("opacity", .9);
            tooltip.html("[Surgeons Name]<br/> (" + d.y + "% from " + surgeonx[i] + " ops)")
                .style("left", (d3.event.pageX + 5) + "px")
                .style("top", (d3.event.pageY - 28) + "px");
        })
        .on("mouseout", function (d) {
            tooltip.transition()
                .duration(500)
                .style("opacity", 0);
        });

    vis.append("text")
        .attr("class", "x label")
        .attr("text-anchor", "end")
        .attr("x", WIDTH / 2)
        .attr("y", HEIGHT - 6)
        .text("No. Operations");

    vis.append("text")
        .attr("class", "y label")
        .attr("text-anchor", "end")
        .attr("y", 6)
        .attr("dy", ".75em")
        .attr("dx", -(HEIGHT / 4))
        .attr("transform", "rotate(-90)")
        .text("Case Complexity Adjusted CR Rupture Rate");

    vis.append("svg:g")
        .attr("class", "legend")
        .attr("dx", 500)
        .attr("transform", "translate(" + (WIDTH - 100) + ",100)")
        .style("font-size", "14px")
        .call(d3.legend);

    vis.append("text")
        .attr("class", "chart-header")
        .style("font-size", "18px")
        .attr("text-anchor", "middle")
        .attr("font-weight", "bold")
        .attr("x", WIDTH / 2)
        .attr("y", 30)
        .text("Cataract Audit - Case Complexity Adjusted PCR Rate")

</script>
