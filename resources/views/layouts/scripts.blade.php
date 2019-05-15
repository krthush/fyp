<!-- Vue Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.0.1/vue-resource.min.js"></script>
<script src="/js/app.js"></script>

<!-- Bootstrap Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<!-- jQuery UI script -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
  $(function(){

    $( "#project-list" ).sortable();
    $( "#project-list" ).sortable( "disable" );

  });
</script>

<script>
  $(function(){

    var edit_project_order = false;

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $("#reorder-projects-button").click(function () {
        $(this).text(function(i, v){
           return v === 'Reorder Projects' ? 'Finish Reorder Projects' : 'Reorder Projects'
        });
        if (edit_project_order == true) {
          $("#project-list > a").unbind('click');
          $("#project-list > a").removeClass("grabbable");
          $("#project-list").sortable( "disable" );

          var projectOrder = $("#project-list").sortable('toArray', {
              attribute: 'data-id'
          });

          // Start of Ajax
          $.ajax({
              url: "{{ route('reorder-projects') }}",
              type:"POST",
              data: {
                  '_method': 'PATCH',
                  'projectOrder': projectOrder,
              },
              success:function(data){
                  console.log(data);
              },
              error:function(){ 
                  alert("Error!!!!");
              }
          }); //end of ajax

          edit_project_order = false;

        } else {
          $("#project-list > a").bind('click', function(e){
                  e.preventDefault();
          });
          $("#project-list > a").addClass("grabbable");
          $("#project-list").sortable( "enable" );

          edit_project_order = true;
        }
    });
  });

</script>

<script type="text/javascript">

  $(function(){

    $( "#project-list" ).sortable({
      beforeStop: function( event, ui ) {

        rankcounter = 1;

        $('.project-list-rank').each(function(i, obj) {
          obj.innerHTML = "Rank: " + rankcounter;
          rankcounter++;
        });

      }
    });

  });

</script>


<script>

$.fn.reverseChildren = function() {
  return this.each(function(){
    var $this = $(this);
    $this.children().each(function(){
      $this.prepend(this);
    });
  });
};

$( "#target" ).click(function() {
  $('#allprojects').reverseChildren();
});

</script>