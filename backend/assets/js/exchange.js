/*
 * 功能：选择不同的货币，然后输入值，换算另外一种货币
 *
 * 由于暂时不是支持所有的货币，所以有的会没有显示
 **/

jQuery.fn.myexchange = function(){

    //上面的币种 的div
    var country_from = $('.t1');
    //上面的币种 的输入框
    var country_from_value = $('.ex1');
    //下面的币种 的div
    var country_to = $('.t2');
    //下面的币种 的输入框
    var country_to_value = $('.ex2');
    //上面的币种符号
    var code_from = "";
    //下面的币种符号
    var code_to = "";
    //所有币种 汇率 的数组
    var rate = [];
    
    //获取所有币种汇率的返回，如果返回失败，整个兑换功能则失效
    var all_country = "AUDCNY,AUDUSD,AUDEUR,AUDCAD,AUDHKD,AUDJPY,AUDNZD,EURUSD,GBPUSD,USDJPY,AUDSGD,CADCNY,USDCAD,AUDGBP,USDCNY,USDHKD,CNYHKD,CADHKD,GBPCNY,GBPHKD,SGDCNY,SGDHKD,JPYCNY,JPYHKD";
    var get_rate = function(){
        try {

            $.ajax({
                url: "http://www.anying.net/MarketWatcher/QuoteService.ashx?callback=?",
                data: "key=763A5EF4-DA6C-4F16-9A45-EF38E13F00C8&symbols=" + all_country,
                contentType: "application/json; charset=utf-8",
                dataType: "jsonp",
                success: get_rate_Succeed,
                failure: function(result) {
                    alert("获取汇率数据失败: " + result);
                },
                error: function(result) {
                    alert("获取汇率数据失败: " + result);
                }
            });

        } catch (e) {
            alert('获取汇率数据失败: ' + e);
        }
    }
    //获取汇率成功回调
    var get_rate_Succeed = function(response){

        //给rate赋值，格式是：rate[m][0] = AUDCNY, rate[m][1] = 1.000, 
        var ticks = response.Ticks;
        for (var i = 0; i < ticks.length; i++) {
            rate.push([ticks[i].Symbol, ticks[i].Bid]);
        }
        //console.log(rate[0][0]);

        //赋值 币种的默认值，输入金额就可以即时显示兑换价格了
        code_from = "AUD";
        code_to = "CNY";
        //币种选择框填充国旗
        country_from.html(get_flag(code_from));
        country_to.html(get_flag(code_to));
        
        //输入金额 换算
        var output_value = 1;
        country_from_value.keyup(function(){
            if(country_from_value.val() > 0)
            {
                output_value = country_from_value.val() * curr_rate(code_from,code_to);
                country_to_value.val(output_value.toFixed(4));
            }
            else
            {
                country_from_value.val("")
                country_to_value.val("");
            }           
        });
        country_to_value.keyup(function(){
            if(country_to_value.val() > 0)
            {
                output_value = country_to_value.val() / curr_rate(code_from,code_to);
                country_from_value.val(output_value.toFixed(4));
            }
            else
            {
                country_from_value.val("")
                country_to_value.val("");
            }       
        });

        //绑定币种的点击动作
        country_from.click(function(){
            $('.flagbox1').slideDown();
        });
        country_to.click(function(){
            $('.flagbox2').slideDown();
        });

         target_flag_action();
    }

    //点击国旗
    $('.flagbox1 img').click(function(){
        //填充国旗
        country_from.html(get_flag($(this).attr('class')));
        code_from = $(this).attr('class');
        country_from_value.val('');
        country_to_value.val('');
        //填充国旗
        flagbox2_img();
    });

    //下面的点击国旗
    var target_flag_action = function(){
        $('.flagbox2 img').click(function(){
            //填充国旗
            country_to.html(get_flag($(this).attr('class')));
            code_to = $(this).attr('class');
            country_from_value.val('');
            country_to_value.val('');
        });
    }
    

    //下面的国旗选择框填充国旗，根据上面的国旗决定下面有什么国旗
    var flagbox2_img = function(){
        //重置下面的国旗
        country_to.html('');
        code_to = 1;

        var flags = all_country.split(',');       
        var insert_img = "";
        //标记
        var j = true;
        for(var i=0; i < flags.length; i++)
        {
            if(flags[i].substr(0,3) == code_from)
            {
                insert_img += get_flag(flags[i].substr(3,3));
                if(j == true)
                {
                    country_to.html(get_flag(flags[i].substr(3,3)));
                    code_to = flags[i].substr(3,3);
                    j = false;
                }
            }
        }
        $('.flagbox2').html(insert_img);
        target_flag_action();
    }

    //国旗选择框的显示隐藏
    $('.flagbox1').mouseout(function(){
        $(this).hide();
    }).mouseover(function(){
         $(this).show();
    });
    country_from.mouseout(function(){
        $('.flagbox1').hide();
    });
    $('.flagbox2').mouseout(function(){
        $(this).hide();
    }).mouseover(function(){
         $(this).show();
    });
    country_to.mouseout(function(){
        $('.flagbox2').hide();
    });

    /*
     *根据币种返回国旗
     *
     * _money : example   AUD
     *
     **/
    var get_flag = function(_money){
        var flag = "";
        switch (_money) {
            case "AUD": flag = "p-aud.png"; break;
            case "CAD": flag = "p-cad.png"; break;
            case "CNY": flag = "p-cny.png"; break;
            case "EUR": flag = "p-eur.png"; break;
            case "GBP": flag = "p-GBP.png"; break;
            case "HKD": flag = "p-hkd.png"; break;
            case "INR": flag = "p-inr.png"; break;
            case "JPY": flag = "p-jpy.png"; break;
            case "KRW": flag = "p-krw.png"; break;
            case "MYR": flag = "p-myr.png"; break;
            case "NZD": flag = "p-NZD.png"; break;
            case "SGD": flag = "p-SGD.png"; break;
            case "THB": flag = "p-thb.png"; break;
            case "TWD": flag = "p-twd.png"; break;
            case "USD": flag = "p-usd.png"; break;
            default: flag = ""; break;
        }
        return '<img src="./assets/images/'+flag+'" class="'+_money+'">';       
    }

    /*
     *根据币种获取当前汇率
     *
     * _money_from : example   AUD
     * _money_to: example   CNY
     *
     **/
    var curr_rate = function(_money_from, _money_to){
        var value = 0;
        for (var i = 0; i < rate.length; i++) {
            if(rate[i][0] == _money_from+_money_to)
            {
                value = rate[i][1];
                break;
            }
            if(rate[i][0] == _money_to+_money_from)
            {
                value = 1 / (rate[i][1]);
                break;
            }
        }
        return value;
    }

    //开始！
    get_rate();
};