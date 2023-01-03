<?php

class Session
{

    /**
     * Constructor which starts an secure session
     */
    public function __construct()
    {
        // Creates a session if there is non yet
        if (session_status() == 1) {
            session_set_cookie_params(6000000, '/', null, false, true);
            session_start();
            $this->getCsrfToken();
        }
    }

    /**
     * Checks if a session is logged in
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
            return true;
        }
        return false;
    }

    /**
     * Create a session
     *
     * @param array $user The user data
     * @return void
     */
    public function createSession($user)
    {
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['rank'] = intval($user['rank']);
        $_SESSION['password_hash'] = md5($user['password']);
    }

    /**
     * Completely deletes session
     *
     * @return void
     */
    public function deleteSession()
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    /**
     * Returns session data
     *
     * @param string $param The param
     * @return string
     */
    public function data($param)
    {
        return isset($_SESSION[$param]) ? e($_SESSION[$param]) : '';
    }

    /**
     * Returns or creates an csrf token when non is set
     *
     * @return string
     */
    public function getCsrfToken()
    {
        return $_SESSION['csrfToken'] ?? $_SESSION['csrfToken'] = bin2hex(
            openssl_random_pseudo_bytes(32)
        );
    }

    /**
     * Checks if given token is same as session token
     *
     * @param string $token
     * @return boolean
     */
    public function isValidCsrfToken($token)
    {
        return $_SESSION['csrfToken'] === $token;
    }
}
