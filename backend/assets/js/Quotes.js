var TickList = new Array();
TickList[0] = "AUDCNY,AUDUSD,AUDEUR,AUDCAD,AUDHKD,AUDJPY";
TickList[1] = "AUDCNY,AUDUSD,AUDEUR,AUDCAD,AUDHKD,AUDJPY,AUDNZD,EURUSD,GBPUSD,USDJPY,AUDSGD,CADCNY,USDCAD,AUDGBP,USDCNY,USDHKD,CNYHKD,CADHKD,GBPCNY,GBPHKD,SGDCNY,SGDHKD,JPYCNY,JPYHKD";



//完整输出的页面不需要加个底部的文字说明
var _info = 0; 

/*首页：获取信息*/
function getQuotes(_curTicks, _have_info) {
    try {
        $.ajax({
            url: "http://58.162.84.87/MarketWatcher/QuoteService.ashx?callback=?",
            data: "key=763A5EF4-DA6C-4F16-9A45-EF38E13F00C8&symbols=" + TickList[_curTicks],
            contentType: "application/json; charset=utf-8",
            dataType: "jsonp",
            success: quoteCallSucceed,
            failure: function(result) {
                alert("failure error: " + result);
            },
            error: function(result) {
                alert("call failed: " + result);
            }
        });
        if (_have_info == 0) {
            _info = 0;
        } else {
            _info = 1;
        }
    } catch (e) {
        alert('Failed to call web service. Error: ' + e);
    }
} 

/*首页：获取成功回调*/
function quoteCallSucceed(response) {
    try {
        var ticks = response.Ticks;
        refreshQuote(ticks);
    } catch (e) {
        alert("quote parse error: " + response);
    }
} 

/*首页：填充数据进入div*/
function refreshQuote(ticks) {
    var _vip_output = "";
    var _online_output = "";
    var _output_head = "";
    var _from_img = "";
    var _to_img = "";
    var count = ticks.length;
    _output_head += '<table width="100%" border="0">' + '<tr>' + '<td>&nbsp;</td>' + '<td>&nbsp;</td>' + '<td>&nbsp;</td>' + '<td class="head">BID买入价</td>' + '<td class="head">ASK卖出价</td>' + '<td class="head">时间</td>' + '</tr>';
    for (var i = 0; i < count; i++) {
        if (ticks[i].Symbol == "AUDCNY") {
            _vip_output = '<tr>' + '<td><img src="./assets/images/p-aud.png" /></td>' + '<td><img src="./assets/images/p-cny.png" /></td>' + '<td><img src="./assets/images/p-' + (ticks[i].UpDown ? 'down' : 'up') + '.gif" /></td>' + '<td class="big">' + ticks[i].Bid + '</td>' + '<td class="big">' + ticks[i].Ask + '</td>' + '<td class="small">' + ticks[i].TickTime + '</td>' + '</tr>';
            if (_info == 1) {
                _vip_output += '<tr>' + '<td colspan="6"><img src="./assets/images/hui-01.gif"></td>' + '</tr>';
            }
        } else {
            switch (ticks[i].Symbol) {
            case "AUDUSD":
                _from_img = "p-aud.png";
                _to_img = "p-usd.png";
                break;
            case "AUDEUR":
                _from_img = "p-aud.png";
                _to_img = "p-eur.png";
                break;
            case "AUDCAD":
                _from_img = "p-aud.png";
                _to_img = "p-cad.png";
                break;
            case "AUDHKD":
                _from_img = "p-aud.png";
                _to_img = "p-hkd.png";
                break;
            case "AUDJPY":
                _from_img = "p-aud.png";
                _to_img = "p-jpy.png";
                break;
            case "AUDNZD":
                _from_img = "p-aud.png";
                _to_img = "p-NZD.png";
                break;
            case "EURUSD":
                _from_img = "p-eur.png";
                _to_img = "p-usd.png";
                break;
            case "GBPUSD":
                _from_img = "p-GBP.png";
                _to_img = "p-usd.png";
                break;
            case "USDJPY":
                _from_img = "p-usd.png";
                _to_img = "p-jpy.png";
                break;
            case "AUDSGD":
                _from_img = "p-aud.png";
                _to_img = "p-SGD.png";
                break;
            case "CADCNY":
                _from_img = "p-cad.png";
                _to_img = "p-cny.png";
                break;
            case "USDCAD":
                _from_img = "p-usd.png";
                _to_img = "p-cad.png";
                break;
            case "AUDGBP":
                _from_img = "p-aud.png";
                _to_img = "p-GBP.png";
                break;
            case "USDCNY":
                _from_img = "p-usd.png";
                _to_img = "p-cny.png";
                break;
            case "JPYHKD":
                _from_img = "p-jpy.png";
                _to_img = "p-hkd.png";
                break;
            case "JPYCNY":
                _from_img = "p-jpy.png";
                _to_img = "p-cny.png";
                break;
            case "SGDHKD":
                _from_img = "p-sgd.png";
                _to_img = "p-hkd.png";
                break;
            case "SGDCNY":
                _from_img = "p-sgd.png";
                _to_img = "p-cny.png";
                break;
            case "GBPHKD":
                _from_img = "p-GBP.png";
                _to_img = "p-hkd.png";
                break;
            case "GBPCNY":
                _from_img = "p-GBP.png";
                _to_img = "p-cny.png";
                break;
            case "CADHKD":
                _from_img = "p-cad.png";
                _to_img = "p-hkd.png";
                break;
            case "CNYHKD":
                _from_img = "p-cny.png";
                _to_img = "p-hkd.png";
                break;
            case "USDHKD":
                _from_img = "p-usd.png";
                _to_img = "p-hkd.png";
                break;
            default:
                _from_img = "p-aud.png";
                _to_img = "p-cny.png";
                break;
            }
            _online_output += '<tr>' + 
                                '<td><img src="./assets/images/' + _from_img + '" /></td>' + 
                                '<td><img src="./assets/images/' + _to_img + '" /></td>' + 
                                '<td><img src="./assets/images/p-' + (ticks[i].UpDown ? 'down' : 'up') + '.gif" /></td>' + 
                                '<td class="big">' + ticks[i].Bid + '</td>' + 
                                '<td class="big">' + ticks[i].Ask + '</td>' + 
                                '<td class="small">' + ticks[i].TickTime + '</td>' + '</tr>';
        }
    }
    if (_info == 1) {
        _online_output += '<tr>' + '<td colspan="6" class="footer"><img src="./assets/images/hui-02.gif"></td>' + '</tr>';
    }
    var _output_foot = "</table>";
    $("#divVIPTicks").html(_output_head + _vip_output + _output_foot);
    $("#divOnlineTicks").html(_output_head + _online_output + _output_foot);
    if (_info == 0) {
        $("tr:odd").css("background", "#e4e9ef");
    }
}