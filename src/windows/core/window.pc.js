$(function() {
    var $t = function(selector) {
        return $("#JtuyoshiCrop").find(selector)
    };
    var SelectMudar_Orientacao = function() {
        if ($t("#SelectOrientacao").val() == "Horizontal") {
            if ($t("#SelectProporcao").val() == "box") {
                var CH = (($t("#Principal").height() / 100) * 80);
                var CW = (CH * 4) / 3;
                if (CW > $t("#Principal").width()) {
                    CW = (($t("#Principal").width() / 100) * 80);
                    CH = (CW / 4) * 3
                }
                var CX = (($t("#Principal").width() - CW) / 2);
                var CY = (($t("#Principal").height() - CH) / 2);
                $t("#SelecaoRecorte").stop().animate({
                    "left": CX,
                    "top": CY,
                    "width": CW,
                    "height": CH
                }, 300);
                $t("#SelecaoRecorte").resizable("destroy").resizable({
                    containment: "parent",
                    aspectRatio: 4 / 3,
                    minWidth: 100,
                    minHeight: 100
                })
            } else if ($t("#SelectProporcao").val() == "wide") {
                var CW = ($t("#Principal").width() / 100) * 80;
                var CH = (CW / 16) * 9;
                var CX = (($t("#Principal").width() - CW) / 2);
                var CY = (($t("#Principal").height() - CH) / 2);
                $t("#SelecaoRecorte").stop().animate({
                    "left": CX,
                    "top": CY,
                    "width": CW,
                    "height": CH
                }, 300);
                $t("#SelecaoRecorte").resizable("destroy").resizable({
                    containment: "parent",
                    aspectRatio: 16 / 9,
                    minWidth: 100,
                    minHeight: 100
                })
            } else {
                if ($t("#Principal").width() < $t("#Principal").height()) {
                    var CW = ($t("#Principal").width() / 100) * 80;
                    var CH = CW;
                    var CX = (($t("#Principal").width() - CW) / 2);
                    var CY = (($t("#Principal").height() - CH) / 2)
                } else {
                    var CH = (($t("#Principal").height() / 100) * 80);
                    var CW = CH;
                    var CX = (($t("#Principal").width() - CW) / 2);
                    var CY = (($t("#Principal").height() - CH) / 2)
                };
                $t("#SelecaoRecorte").stop().animate({
                    "left": CX,
                    "top": CY,
                    "width": CW,
                    "height": CH
                }, 300).resizable("destroy").resizable({
                    containment: "parent",
                    minWidth: 100,
                    minHeight: 100
                })
            }
        } else {
            if ($t("#SelectProporcao").val() == "box") {
                var CH = (($t("#Principal").height() / 100) * 60);
                var CW = (CH * 3) / 4;
                var CX = (($t("#Principal").width() - CW) / 2);
                var CY = (($t("#Principal").height() - CH) / 2);
                $t("#SelecaoRecorte").stop().animate({
                    "left": CX,
                    "top": CY,
                    "width": CW,
                    "height": CH
                }, 300).resizable("destroy").resizable({
                    containment: "parent",
                    aspectRatio: 3 / 4,
                    minWidth: 100,
                    minHeight: 100
                });
                $t("#SelecaoRecorte").resizable("option", "aspectRatio", 3 / 4)
            } else if ($t("#SelectProporcao").val() == "wide") {
                var CH = (($t("#Principal").height() / 100) * 80);
                var CW = (CH * 9) / 16;
                var CX = (($t("#Principal").width() - CW) / 2);
                var CY = (($t("#Principal").height() - CH) / 2);
                $t("#SelecaoRecorte").stop().animate({
                    "left": CX,
                    "top": CY,
                    "width": CW,
                    "height": CH
                }, 300).resizable("destroy").resizable({
                    containment: "parent",
                    aspectRatio: 9 / 16,
                    minWidth: 100,
                    minHeight: 100
                })
            } else {
                if ($t("#Principal").width() < $t("#Principal").height()) {
                    var CW = ($t("#Principal").width() / 100) * 80;
                    var CH = CW;
                    var CX = (($t("#Principal").width() - CW) / 2);
                    var CY = (($t("#Principal").height() - CH) / 2)
                } else {
                    var CH = (($t("#Principal").height() / 100) * 80);
                    var CW = CH;
                    var CX = (($t("#Principal").width() - CW) / 2);
                    var CY = (($t("#Principal").height() - CH) / 2)
                };
                $t("#SelecaoRecorte").stop().animate({
                    "left": CX,
                    "top": CY,
                    "width": CW,
                    "height": CH
                }, 300).resizable("destroy").resizable({
                    containment: "parent",
                    minWidth: 100,
                    minHeight: 100
                })
            }
        }
    };
    $t("#SelectOrientacao, #SelectProporcao").change(function() {
        SelectMudar_Orientacao()
    })
});