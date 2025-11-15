const quick_day = $("#quick-date");
const category = $("#categories");
const payment_method = $("#payment_method");
const start_date = $("#start-date");
const end_date = $("#end-date");
const custom_date = $("#custom-date-filter");

//Click On Apply Filter
$('#apply-filter-btn').on("click",function(){
  applyFilter();
})

//Click On Reset Button
$("#reset-filter-btn").on("click",function(){
    category.val("0").trigger("change");
    quick_day.val("month").trigger('change');
    payment_method.val("all").trigger('change');
    custom_date.addClass('d-none');
applyFilter();
})

//Change On Quick Date
quick_day.on('change',function(){
    const selected_val = $(this).val();
    if(selected_val==='custom_date'){
        custom_date.removeClass('d-none');
    }else{
        custom_date.addClass('d-none');
    }
})
