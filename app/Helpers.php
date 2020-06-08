<?php
    /**
     * Additional application helpers
     *
     * @author Davi Souto
     * @since 05/06/2020
     */

    /////////////////////////////////////////////////////////

    /**
     * Get club code of logged user
     *
     * @return string
     * @author Davi Souto
     * @since 05/06/2020
     */
    if (! function_exists('getClubCode'))
    {
        function getClubCode()
        {
            if ($club_code = Auth::guard()->user()->only(['club_code']))
                return $club_code['club_code'];

            return false;
        }
    }

    /////////////////////////////////////////////////////////

     /**
     * Redirect the user no matter what. No need to use a return
     * statement. Also avoids the trap put in place by the Blade Compiler.
     *
     * @param string $url
     * @param int $code http code for the redirect (should be 302 or 301)
     * @copyright https://stackoverflow.com/questions/25581353/redirection-in-laravel-without-return-statement
     */
    if (! function_exists('redirect_now'))
    {
        function redirect_now($url, $code = 302)
        {
            try {
                \App::abort($code, '', ['Location' => $url]);
            } catch (\Exception $exception) {
                // the blade compiler catches exceptions and rethrows them
                // as ErrorExceptions :(
                //
                // also the __toString() magic method cannot throw exceptions
                // in that case also we need to manually call the exception
                // handler
                $previousErrorHandler = set_exception_handler(function () {
                });
                restore_error_handler();
                call_user_func($previousErrorHandler, $exception);
                die;
            }
        }
    }