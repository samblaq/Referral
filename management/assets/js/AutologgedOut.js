$(function(){

    function timeChecker(){
        setInterval(function(){
            var storedTimeStamp = sessionStorage.getItem("LastTimeStamp");
            timeCompare(storedTimeStamp);
        }, 3000);
    }

    function timeCompare(timeString){
        var currentTime = new Date();
        var pastTime    = new Date(timeString);
        var timeDiff    = currentTime - pastTime;
        var minPast     = Math.floor((timeDiff/60000));

        if (minPast > 15) {
            sessionStorage.removeItem("LastTimeStamp");
            window.location = "logout.php";
            return false;
        }else{
            console.log("User stilll active");
        }
    }

    $(document).mousemove(function(){
        var timeStamp = new Date();
        sessionStorage.setItem("LastTimeStamp" , timeStamp);
        
    });

    timeChecker();
});