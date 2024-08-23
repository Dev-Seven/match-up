        <aside class="control-sidebar control-sidebar-dark">
        </aside>
        <footer class="main-footer">
            <strong>@lang('messages.copyright') &copy; {{date('Y')}} <a href="javascript::void(0)">{{env('APP_NAME')}}</a>.</strong>
            @lang('messages.all_right_reserved').
        </footer>
        </div>

        <!-- Popup modal for logout start -->
        <div class="modal fade" id="logoutModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@lang('messages.are_you_sure')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @lang('messages.are_you_sure_to_complete_your_session')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.close')</button>
                        <a href="{{route('admin.logout')}}" class="btn btn-danger btn_loader">@lang('messages.logout')</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Popup modal for logout end -->

        <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <script src="{{asset('dist/js/adminlte.js')}}"></script>
        <script src="{{asset('dist/js/demo.js')}}"></script>
        <script src="{{asset('plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
        <script src="{{asset('plugins/raphael/raphael.min.js')}}"></script>
        <script src="{{asset('plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
        <script src="{{asset('plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
        <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
        <script src="{{asset('dist/js/pages/dashboard2.js')}}"></script>

        <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
        <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
        <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
        <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
        <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
        <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

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

            $(document).on('click','.logoutModel',function(){
                $('#logoutModel').modal();
            })

            $(document).ready(function(){
              $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
        </body>

        </html>