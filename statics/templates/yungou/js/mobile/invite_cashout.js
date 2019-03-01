$(function(){
    ready();
})

// 初始化函数
function ready () {
    // 层级切换
    var hash = location.hash.indexOf('recharge') > -1 ? 1 : 0;
    switch (hash) {
        case 1:
            $('.subMenu a:last-child').addClass('current').prev().removeClass('current');
            $('#divSQTX').hide();
            $('#divSQCZ').show();
            break;

        default:
            //
            break;
    }

    $(".subMenu a").click(function (e) {
        var elm = $(this),
            show = '#' + elm.data('show'),
            hide = '#' + elm.data('hide');

        elm.addClass('current').siblings('a').removeClass('current');
        $(show).show();
        $(hide).hide();
    });

    $("#linkApply").click(function () {
        $("#divSQTX").show();
        $("#divSQCZ").hide();
    });

    $("#linkRecharge").click(function () {
        console.log(11);
        $("#divSQTX").hide();
        $("#divSQCZ").show();

    });

    $("#btnSQCZ").click(function () {
        var CZMoney = parseInt(trim($("#txtCZMoney").val()));
        console.log(CZMoney);
        if (!CZMoney) {
            layer.msg('请输入充值金额');
            return false;
        } else if (CZMoney > balance) {
            layer.msg('余额不足');
            return false;
        } else
            return true;
    });

    var payeeList = $('#payee-list'),
        bankList = $('#bank-list'),
        forBankList = $('#for_bank_list');

    $('#for_payee_list').click(function (e) {
        payeeList.toggleClass('show');
    });

    $('#payee-list').on('click', 'dd', function (e) {
        var elm = $(this),
            payee = elm.data('payee'),
            key = elm.data('key'),
            payeeBankNo = elm.data('bankno'),
            payeeMobile = elm.data('mobile');

        $('input[name=\'txtUserName\']').val(payee),
            $('input[name=\'txtBankName\']').val(key),
            $('#select_bank').html(bankArr[key]['name'].replace('<br>', '')),
            $('input[name=\'txtBankNo\']').val(payeeBankNo),
            $('input[name=\'txtPhone\']').val(payeeMobile);

        payeeList.removeClass('show');

    });

    $('#payee-exit').click(function (e) {
        payeeList.removeClass('show');
    });

    forBankList.click(function (e) {
        bankList.toggleClass('show');
    });

    $('li.bank').click(function (e) {
        var elm = $(this),
            key = elm.data('key');
            name = elm.children('p').text();
        if (key != 'none') {
            $('#txtBankName').val(name);
            $('#select_bank').html(name.replace('<br>', ''));
        }

        bankList.removeClass('show');
    });
}

//数字
function isBankNumber(s) {
    var patrn = /^([1-9]{1})(\d{14}|\d{15}|\d{16}|\d{17}|\d{18})$/;
    if (!patrn.exec(s)) {
        return false;
    }
    return true;
}

//校验手机号码：必须以数字开头
function isMobile(s) {
    var patrn = /^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|19[0-9]{9}$/;
    if (!patrn.exec(s)) {
        return false;
    }
    return true;
}

//检验姓名：姓名是2-15字的汉字
function isCardName(s) {

    var patrn = /^\s*[\u4e00-\u9fa5]{1,}[\u4e00-\u9fa5.·]{0,15}[\u4e00-\u9fa5]{1,}\s*$/;
    if (!patrn.exec(s)) {
        return false;
    }
    return true;
}

function trim(s) {
    if (s.length > 0) {
        if (s.charAt(0) == " ")
            s = s.substring(1, s.length);
        if (s.charAt(s.length - 1) == " ")
            s = s.substring(0, s.length - 1);

        if (s.charAt(0) == " " || s.charAt(s.length - 1) == " ")
            return trim(s);
    }
    return s;
}

function confirmInfo() {
    var Money = trim($("#txtAppMoney").val()),
        UserName = trim($("#txtUserName").val()),
        BankName = $("#txtBankName").val(),
        BankNo = trim($("#txtBankNo").val()),
        Phone = $("#txtPhone").val(),
        Province = $("#Province").val(),
        City = $("#City").val();

    if (Money == "" || Money > 50000 || Money < 200) {
        layer.msg("提现金额必须在200元至5万元之间");
        return false;

    } else if (Money > balance) {
        layer.msg("余额不足");
        return false;

    } else if (UserName == '' || !isCardName(UserName)) {
        layer.msg("请检查收款人姓名");
        return false;

    } else if (BankName == "") {
        layer.msg("请选择银行");
        return false;

    } else if (BankNo == '' || !isBankNumber(BankNo)) {
        layer.msg("请检查收款卡号");
        return false;

    } else if (Phone == "" || !isMobile(Phone)) {
        layer.msg("请检查手机号码");
        return false;
    } else if (Province == null) {
        layer.msg("请选择省份");
        return false;
    } else if (City == null) {
        layer.msg("请选择城市");
        return false;
    }

    $.post('/index.php/mobile/invite/bankCardVerify', {name: UserName, bank: BankNo}, function (json) {
        json = JSON.parse(json);
        if (json.success == false){
            layer.alert(json.message, {
                icon: 2
            }, function (index) {
                layer.close(index);
            });
        }else {
            $('#btnSQTX').attr('disabled', 'disabled');
            layer.load();
            $('#cashout').submit();
        }
    });
}