@if (Session::has('success'))
<script type="text/javascript">
    toastr.options =
      {
        // "closeButton" : true,
        // "progressBar" : true,
        //"positionClass" : 'toast-bottom-right'
      }
    toastr.success("{{ session('success') }}");
</script>
@endif
@if (Session::has('danger'))
<script type="text/javascript">
    toastr.options =
      {
        // "closeButton" : true,
        // "progressBar" : true,
        //"positionClass" : 'toast-bottom-right'
      }
    toastr.error("{{ session('danger') }}");
</script>
@endif