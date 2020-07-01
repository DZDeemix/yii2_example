$(document).ready(function() {
	var files;
	var addNewItem;
	$("#phone").mask("");

	$('#sold_on')
		.mask('99.99.9999')
		.datepicker({
			changeMonth: true,
			changeYear:  true
		});

	$('select.products-list').select2();

	function FindFile() {
		document.getElementById('input_image_file').click();
	}

	$('#upload_files_img').click(function() {
		FindFile();
	});
	/*Передача файлов на сервер через Ajax*/
	$('input[type=file]').change(function(event) {
		files = this.files;
		console.log(files);
		event.stopPropagation();
		event.preventDefault();
		var data = new FormData();
		$.each(files, function(key, value) {
			data.append(key, value);
		});
		$.ajax({
			url:         '/sales/sales/upload-images',
			type:        'POST',
			data:        data,
			cache:       false,
			dataType:    'json',
			processData: false,
			contentType: false,
			success:     function(data) {
				//console.log(data);
				$('#upload_files_img').hide();
				$('.red_link_delete').show();
				$('.sale_register').show();
				$('.add_new_sales_item').show();
				$('#total_sale_information').show();
				$('#add_new_sale_position').show();
				$('.margin_row_add_position').show();
				addNewItem = $('.add_new_sales_item').html();
				var uploadingImg = '';

				for (var i = 0; i < data.length; i++) {
					if ('pdf' == data[i].split('.').pop()) {
						$("#img_add").append("<a target='_blank' href='/data/sales/" + data[i] + "'"
							+ " class='uploading_img'><img src='/images/pdf.png'/></a>");
					}
					else {
						$("#img_add").append("<a target='_blank' href='/data/sales/" + data[i] + "'"
							+ " class='uploading_img'>"
							+ "<img src='/data/sales/" + data[i] + "'></a>");
					}
					$("<input class='files' type='hidden' name='files[]' value='" + data[i] + "'/>")
						.appendTo('#saleForm');
				}
				$("#upload-errors li").remove();
			},
			error:       function(xhr) {
				var data = xhr.responseJSON;
				if ("errors" in data) {
					$("#upload-errors li").remove();
					for (var i = 0; i < data.errors.length; i++) {
						$("#upload-errors").append("<li>" + data.errors[i] + "</li>");
					}
				}
			}
		});
	});

	/* Удаление загруженных фотографий */
	$('.red_link_delete').click(function() {
		$('.uploading_img').remove();
		$('.red_link_delete').hide();
		$('.add_new_sales_item').hide();
		$('#upload_files_img').show();
		$('#add_new_sale_position').show();
		$('.margin_row_add_position').hide();
		$('.sale_register').hide();
		$('#total_sale_information').hide();
		$('#input_image_file').val(null);
		$('input.files').remove();
	});

	/*Клонирование полей позиций заказа  */
	$('#add_new_sale_position').click(function() {
		$('.add_new_sales_item.hidden').first().removeClass('hidden');
	});

	/*Создание покупки*/
	$('#register_new_sale').click(function(e) {
		e.preventDefault();
		var data = $('#saleForm').serialize();
		$.ajax({
			type:    'POST',
			url:     '/sales/sales/add-sale',
			data:    data,
			success: function(data) {
				//console.log(data);
				if (data.redirect) {
					window.location = data.redirect;
				}
			},
			error:   function(xhr) {
				var data = xhr.responseJSON;
				if ("errors" in data) {
					$("#sale-errors li").remove();
					for (var i = 0; i < data.errors.length; i++) {
						$("#sale-errors").append("<li>" + data.errors[i] + "</li>");
					}
				}
			}
		});
	});
	/*Видимость модалки успешного добавления покупки*/
	if ($('#modal_ok').data('showmodal')) {
		$('#saleOkForm').modal('show');
	}
	$('#close_modal_ok').click(function() {
		$('#saleOkForm').modal('hide');
	})
});

function updateBonuses() {
	var bonusBall = 0;

	$('.add_new_sales_item').each(function() {
		var itemCount = $(this).find('.count_sale_items').val();
		itemCount = parseInt(itemCount);

		var itemBonus = $(this).find('.positions option:selected').data('bonus');
		itemBonus = parseInt(itemBonus);

		if (itemCount > 0 && itemBonus > 0) {
			bonusBall = bonusBall + (itemCount * itemBonus);
		}
	});

	if (bonusBall > 0) {
		$('#total_sum_sale').val(bonusBall);
	}
	else {
		$('#total_sum_sale').val('');
	}
}

$(document)
	.on("click", ".fa.remove", function() {
		$(this).closest('.add_new_sales_item').remove();
		updateBonuses();
	})
	.on("change", ".count_sale_items", function() {
		updateBonuses();
	})
	.on("change", ".positions", function() {
		updateBonuses();
	})
	.on("keyup", ".count_sale_items", function() {
		updateBonuses();
	});