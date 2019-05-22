<!-- Vue Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.0.1/vue-resource.min.js"></script>
<script src="/js/app.js"></script>

<!-- Bootstrap Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<!-- Bootstrap Toggle -->
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>

<!-- jQuery UI script -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- Sortable jQuery component intialization to be make rank selecetion draggable -->
<script>
  $(function(){

    $( "#project-list" ).sortable();
    $( "#project-list" ).sortable( "disable" );

  });
</script>

<!-- Logic for draggable sorting of selected projects -->
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

<!-- Logic to reassign rank number -->
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

<!-- Script for flipping direction of search order -->
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

<!-- Script to make hoverable table clickable -->
<script type="text/javascript">
  $(document).ready(function($) {
      $(".link-table-row").click(function() {
          window.document.location = $(this).data("href");
      });
  });
</script>

<!-- Super Admin Scripts -->
@isset($superadmin)
<script type="text/javascript">
  $(function() {

    $('#toggle-project-viewing').change(function() {
      // Start of Ajax
      $.ajax({
          url: "{{ route('toggle-project-viewing') }}",
          type: 'GET', //THIS NEEDS TO BE GET
          success: function (data) {
              console.log(data);
          },
          error: function() { 
               console.log(data);
          }
      }); // End of Ajax
    });

    $('#toggle-project-selection').change(function() {
      // Start of Ajax
      $.ajax({
          url: "{{ route('toggle-project-selection') }}",
          type: 'GET', //THIS NEEDS TO BE GET
          success: function (data) {
              console.log(data);
          },
          error: function() { 
               console.log(data);
          }
      }); // End of Ajax
    });

    $('#toggle-project-first-matching').change(function() {
      // Start of Ajax
      $.ajax({
          url: "{{ route('toggle-project-first-matching') }}",
          type: 'GET', //THIS NEEDS TO BE GET
          success: function (data) {
              console.log(data);
          },
          error: function() { 
               console.log(data);
          }
      }); // End of Ajax
    });

    $('#toggle-project-all-matching').change(function() {
      // Start of Ajax
      $.ajax({
          url: "{{ route('toggle-project-all-matching') }}",
          type: 'GET', //THIS NEEDS TO BE GET
          success: function (data) {
              console.log(data);
          },
          error: function() { 
               console.log(data);
          }
      }); // End of Ajax
    });

  });  
</script>
@endisset