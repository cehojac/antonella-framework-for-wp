$( document ).ready(function() {
   $('select[name="tipo"]').bind("change", function() {
       var val=$(this).val();
       if(val=='hotel')
       {
        $(".apartamento").hide(0);
        $(".hotel").show(0);
       }
       else
       {
        $(".apartamento").show(0);
        $(".hotel").hide(0);
       }
        
   });
    function search(){

                      var title=$("#search").val();

                      if(title!=""){
                        $("#result").html(' <div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>');
                         $.ajax({
                            type:"post",
                            url:"./ajax",
                            data:{request:"search",data:title}, //"title="+title,
                            success:function(data){
                                $("#result").html(data);
                               
                             }
                          });
                      }
                  
       
                      

                     
                 }

                  $("#button").click(function(){
                  	 search();
                  });

                  $('#search').keyup(function(e) {
                     if($(this).val().length>=3)
                      {
                        search();
                      }
                  });
});