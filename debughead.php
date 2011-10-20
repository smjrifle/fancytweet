<?php
        function debugHead($o) {
            echo '<script type="text/javascript">
                    window.addEventListener("load",
                        function(){
                            var a="';
            ob_start();
            var_dump($o);
            $op = ob_get_contents();
            ob_end_clean();
            $op = str_replace('"', '\"', str_replace(')', '\\)', str_replace('(', '\\(', $op)));
            $op=str_replace(array("\n"), '--break--', $op);
            echo $op;
            echo '";
                            var p=a.split("--break--");
                            for (var i=0;i<p.length;i++)
                                if (p[i]!="") console.log(p[i]);
                        }
                    ,false);
            </script>';
        }
        ?>
