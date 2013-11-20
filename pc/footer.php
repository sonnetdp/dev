	<footer>
      <p>Copyright &copy; <a href="http://www.cactsgroup.com">CACTS</a></p>
    </footer>
  </div>
  <p>&nbsp;</p>
<!-- javascript at the bottom for fast page loading -->
  <!--script type="text/javascript" src="<?php echo URL; ?>js/jquery.js"></script-->
  <script type="text/javascript" src="<?php echo URL; ?>js/jquery.easing-sooper.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>js/modernizr-1.5.min.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>js/jquery-1.8.3.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>js/jquery-ui.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>js/jquery.sooperfish.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>js/image_fade.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('ul.sf-menu').sooperfish();
    });
  </script>
  <script>
    $(function() {
        $( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
    </script>
    <script>
    $(function() {
        $( "#startDate" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
			changeYear: true,
            numberOfMonths: 3,
            onClose: function( selectedDate ) {
                $( "#endDate" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#endDate" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
			changeYear: true,
            numberOfMonths: 3,
            onClose: function( selectedDate ) {
                $( "#startDate" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
    </script>
  </body>
</html>