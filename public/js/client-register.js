(function ($) {

    $(".add_new_address").click(function () {
        $("#address_footer").hide();
        var lastNo, originalId;
        var arrRowNo = [];
        var baseTbl = $("#address-accordion");
        originalId = baseTbl.find('.address_block:last').attr('id');
        var cloned = $("#address_clone_element").clone().appendTo('#address-accordion').wrap('<tr class="address-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
        //var cloned = baseTbl.append("<tr class='address-base-row' style='display: block;'><td style='width: 100%;display: inline-table;'>"+$("#address_clone_element").clone().html()+"</td></tr>");
        if (originalId)
            arrRowNo = originalId.split("-");

        if (arrRowNo.length > 1) {
            lastNo = arrRowNo[1];
        } else {
            lastNo = 0;
        }

        var newId = 'address_collapse-' + (parseInt(lastNo) + 1);
        cloned.show();
        cloned.find(".address_block").attr('id', newId);
        cloned.find(".address_header").attr('href', '#' + newId);
        cloned.find(".address_row_no").html($(".address-base-row").length);

        cloned.find(".arr_address_address1").attr('name', "address[" + (parseInt(lastNo) + 1) + "][address1]");
        cloned.find(".arr_address_address2").attr('name', "address[" + (parseInt(lastNo) + 1) + "][address2]");
        cloned.find(".arr_address_state").attr('name', "address[" + (parseInt(lastNo) + 1) + "][city]");
        cloned.find(".arr_address_city").attr('name', "address[" + (parseInt(lastNo) + 1) + "][state]");
        cloned.find(".arr_address_country").attr('name', "address[" + (parseInt(lastNo) + 1) + "][country_id]");
        cloned.find(".arr_address_zip").attr('name', "address[" + (parseInt(lastNo) + 1) + "][zipcode]");

        cloned.find('input[type=text]').val('');
    })

    $(".delete_address").click(function (ele) {
        $(this).closest(".address-base-row").remove();

        $(".address-base-row").each(function (index) {
            $(this).find(".address_row_no").html(index + 1);
        });

        if ($(".address-base-row").length < 1) {
            $("#address_footer").html('<span>No record added</span>');
            $("#address_footer").show();
        }
    })

    function addNewContact() {
        $("#contacts_footer").hide();
        var lastNo, originalId;
        var arrRowNo = [];
        var baseTbl = $("#contact-accordion");
        originalId = baseTbl.find('.contact_block:last').attr('id');
        var cloned = $("#contact_clone_element").clone().appendTo('#contact-accordion').wrap('<tr class="contact-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
        if (originalId)
            arrRowNo = originalId.split("-");

        if (arrRowNo.length > 1) {
            lastNo = arrRowNo[1];
        } else {
            lastNo = 0;
        }
        var newId = 'contact_collapse-' + (parseInt(lastNo) + 1);
        cloned.css('display', 'block');
        cloned.find(".contact_block").attr('id', newId);
        cloned.find(".contact_header").attr('href', '#'+newId);
        cloned.find(".contact_row_no").html($(".contact-base-row").length);

        cloned.find(".arr_contacts_fax").attr('name', "contacts["+(parseInt(lastNo) + 1)+"][fax]");
        cloned.find(".arr_contacts_phone").attr('name', "contacts["+(parseInt(lastNo) + 1)+"][phone]");
        cloned.find(".arr_contacts_mobile").attr('name', "contacts["+(parseInt(lastNo) + 1)+"][mobile]");
        cloned.find(".arr_contact_type").attr('name', "contacts["+(parseInt(lastNo) + 1)+"][contact_type_id]");
    }

    function deleteContact(ele) {
        $(ele).closest(".contact-base-row").remove();

        $(".contact-base-row").each(function (index) {
            $(this).find(".contact_row_no").html(index + 1);
        });

        if ($(".contact-base-row").length < 1) {
            $("#contacts_footer").html('<span>No record added</span>');
            $("#contacts_footer").show();
        }
    }

    function deleteRows(ele)
    {
        $(ele).closest('tr').remove();

        if ($(ele).parent('tbody').find("tr").length < 1) {
            var footerEle = $(ele).parent().find("tfoot tr td");
            $(footerEle).html('<span>No record added</span>');
            $(footerEle).show();
        }
    }

    function addNewContactInfoField(type) {
        var cloned;

        switch (type)
        {
            case 'email' :
                $("#emails_tbl_footer").hide();
                cloned = $("#contact_email_clone_row").clone().appendTo("#rows_client_email");
                break;
            case 'phone' :
                $("#phone_tbl_footer").hide();
                cloned = $("#contact_phone_clone_row").clone().appendTo("#rows_client_phone");
                break;
            case 'mobile' :
                $("#mobile_tbl_footer").hide();
                cloned = $("#contact_mobile_clone_row").clone().appendTo("#rows_client_mobile");
                break;
            case 'fax' :
                $("#fax_tbl_footer").hide();
                cloned = $("#contact_fax_clone_row").clone().appendTo("#rows_client_fax");
                break;
            default:
                console.log('no row added');
        }
        cloned.removeAttr('id');
    }

})(jQuery)