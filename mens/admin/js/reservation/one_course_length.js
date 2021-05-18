// JavaScript Document
$(".checked_course, .checked_parts").on("click",function(){
  time_summation();
  check_num();
});
$("#parts_result_btn").on('click',function(){
  return_memo();
});
function time_summation(){
  var $this,total_time,$total_time;
  total_time = 0;
  $total_time = $("#total_time");
  $(".checked_course:checked, .checked_parts:checked").each(function() {
    $this = $(this);
    total_time += Number($this.parents("td").next().text());
  });
  $total_time.text(total_time);
};
function check_num(){
  var $checked_parts,$checked_course,checked_parts_num,checked_course_num,$checked;
  $checked_parts = $("#checked_parts"),
  $checked_course = $("#checked_course");
  checked_parts_num = 0,checked_course_num = 0,
  $checked = $(".checked_course:checked, .checked_parts:checked");
  $checked.each(function() {
    $this = $(this);
    if($this.is(".checked_course")){
      checked_course_num++;
    }else{
      checked_parts_num++;
    }
  });
  $checked_parts.text(checked_parts_num);
  $checked_course.text(checked_course_num);
}
function return_memo(){
  var important_remark,$this,$result_text;
  important_remark = document.getElementById("important_remark");
  $result_text = "";
  $(".checked_course:checked, .checked_parts:checked").each(function() {
    $this = $(this);
    $result_text += $this[0].nextSibling.nodeValue + ",";
    return $result_text;
  });
  important_remark.innerHTML += "1回希望：" + $result_text + "\n";
  return false;
}