import React, { useState } from 'react';
import { LoginPage } from '../LoginPage/LoginPage';
import RegisterPage from '../RegisterPage/RegisterPage';
import { useAuth } from "../../Context";
import  {UserAvatarDropdown}  from '../Avatar/Avatar';



const SibaHeader = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [showLogin, setShowLogin] = useState(false);
  const [showRegister, setShowRegister] = useState(false)
  const { isRegisteredTo, user, isLoggedIn } = useAuth();

  console.log("Is registered:",isRegisteredTo)
  console.log("Is logged in:",isLoggedIn)

  const toggleMenu = (e) => {
    e.preventDefault()
    setIsMenuOpen(!isMenuOpen);
  };

  const toggleRegister = (e) => {
    e.preventDefault();
    setShowRegister(!showRegister)
  }

  const toggleLogin = (e) => {
    e.preventDefault();
    setShowLogin(!showLogin)
  }

  const getInitials = (firstName, lastName) => {
    if (!firstName || !lastName) return "";
    return `${firstName[0]}${lastName[0]}`;
  };

  const handleLogout = () => {
    console.log('User logged out');
  };

  return (
    <header className="bg-blue-500">
      <div className="container mx-auto flex justify-between items-center p-4">
        <div className="text-white text-2xl font-bold">PhrontLyne Logo</div>
        <nav className="hidden md:flex space-x-4">
          {
            (isRegisteredTo || isLoggedIn) ? (
              <>
                <UserAvatarDropdown
                  firstName={user.first_name}
                  lastName={user.last_name}
                  onLogout={handleLogout}
                />
              </>
              ) :
              <>
                <a href="#" onClick={toggleLogin} className="text-white hover:text-gray-300">Login</a>
                <a href="#" className="text-white hover:text-gray-300">Salvages</a>
                <a href="#" className="text-white hover:text-gray-300">My Bids</a>
                <button onClick={toggleRegister} class="bg-white hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-3 border border-blue-500 hover:border-transparent rounded">
                  Register
                </button>              
              </>
          }
        </nav>
        <div className="md:hidden">
          <button onClick={toggleMenu} className="text-white focus:outline-none">
            <svg
              className="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M4 6h16M4 12h16m-7 6h7"
              ></path>
            </svg>
          </button>
        </div>
      </div>
      <div className={`md:hidden ${isMenuOpen ? 'fixed inset-0 bg-blue-500 z-50' : 'hidden'}`}>
        <div className="container mx-auto flex flex-col items-center justify-center h-full space-y-4">
          <button onClick={()=>setShowLogin(true)} className="text-white text-2xl hover:text-gray-300">Login</button>
          <a href="#" className="text-white text-2xl hover:text-gray-300">Salvages</a>
          <a href="#" className="text-white text-2xl hover:text-gray-300">My Bids</a>
          <button onClick={toggleMenu} className="text-white mt-4">
            Close
          </button>
        </div>
        {showLogin ? (
          <>
           <LoginPage/>
          </>
        ) : null}
        {showRegister ? (
          <>
           <RegisterPage/>
          </>
        ) : null}
      </div>
    </header>
  );
};

export default SibaHeader;


