<?php 
    if (isset($error)) {
        echo "<div style='background-color:red' id=\"note\">
                $error
            </div>"; 
    }elseif (isset($success)){
        echo "<div style='background-color:green' id=\"note\">
                $success
            </div>";
    }elseif (isset($AccountLength)){
        echo "<div style='background-color:green' id=\"note\">
                $AccountLength
            </div>";
    }elseif (isset($scsuccess)){
        echo "<div style='background-color:green' id=\"note\">
                $scsuccess
            </div>";
    }elseif (isset($EditSuccess)){
        echo "<div style='background-color:green' id=\"note\">
                $EditSuccess
            </div>";
    }elseif (isset($errorInsert)){
        echo "<div style='background-color:red' id=\"note\">
                $errorInsert
            </div>";
    }elseif (isset($InvalidAccountNumber)){
        echo "<div style='background-color:red' id=\"note\">
                $InvalidAccountNumber
            </div>";
    }elseif (isset($scerrorInsert)){
        echo "<div style='background-color:red' id=\"note\">
                $scerrorInsert
            </div>";
    }elseif (isset($notNumeric)){
            echo "<div style='background-color:red' id=\"note\">
            $notNumeric
         </div>";
    }elseif (isset($scnotNumeric)){
            echo "<div style='background-color:red' id=\"note\">
            $scnotNumeric
         </div>";
    }elseif (isset($scnotNumericAccount)){
            echo "<div style='background-color:red' id=\"note\">
            $scnotNumericAccount
         </div>";
    }elseif (isset($notnumeric)){
            echo "<div style='background-color:red' id=\"note\">
            $notnumeric
         </div>";
    }elseif (isset($empty)){
            echo "<div style='background-color:red' id=\"note\">
            $empty
         </div>";
    }elseif (isset($EmptyPassword)){
            echo "<div style='background-color:red' id=\"note\">
            $EmptyPassword
         </div>";
    }elseif (isset($passwordLength)){
            echo "<div style='background-color:red' id=\"note\">
            $passwordLength
         </div>";
    }
?>