		<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
        <script src="{{asset('js/jquery.validate.min.js')}}"></script>
		<script src="{{asset('js/additional-methods.min.js')}}"></script>
		<script src="{{asset('js/jquery_validation.js')}}"></script>
		<script src="{{asset('js/custom.js')}}"></script>
		<script type="text/javascript">
            $(document).on('click','.btn_loader',function(){
                var $this = $(this);
                var html = $this.html();

                var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i> @lang("messages.loading")';
                $(this).html(loadingText);
                $(this).prop("disabled", true);

                setTimeout(function(){ 
                    $('.btn_loader').html(html);
                    $('.btn_loader').prop("disabled", false);
                }, 5000);
            });
        </script>
    </body>
</html>
