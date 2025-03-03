import React, { useContext, useState } from "react";
  import { Link, useNavigate } from "react-router-dom";
  import { AuthContext } from "../AuthContext";

  const Navbar = () => {
    const { authState, logout } = useContext(AuthContext);
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const navigate = useNavigate();

    const toggleMenu = () => setIsMenuOpen(!isMenuOpen);

    const handleLogout = () => {
      logout();
      navigate("/login");
    };

    return (
      <nav className="bg-gray-900 text-white p-4 fixed top-0 left-0 right-0 w-full shadow-lg z-50">
        <div className="container mx-auto flex justify-between items-center">
          {/* Logo */}
          <div className="flex items-center">
            <img src="/logo.png" alt="Logo" className="h-10 w-10 mr-2" />
            <span className="text-2xl font-bold">SIT-Game</span>
          </div>

          {/* Desktop Navigation */}
          <div className="hidden md:flex space-x-6">
            {/* Always visible links */}
            <Link to="/" className="hover:text-blue-400 transition duration-200">
              Home
            </Link>
            <Link
              to="/blogs"
              className="hover:text-blue-400 transition duration-200"
            >
              Blogs
            </Link>
            <Link
              to="/games-overview"
              className="hover:text-blue-400 transition duration-200"
            >
              Spelkalender
            </Link>
            <Link
              to="/predict-result"
              className="hover:text-blue-400 transition duration-200"
            >
              Predict
            </Link>

            {/* Links based on user roles */}
            {authState.isLoggedIn && authState.role === "beheer" && (
              <Link
                to="/admin"
                className="hover:text-blue-400 transition duration-200"
              >
                Admin Dashboard
              </Link>
            )}
            {authState.isLoggedIn && (
              <Link
                to="/user-profile"
                className="hover:text-blue-400 transition duration-200"
              >
                Profiel
              </Link>
            )}

            {/* Logout link if user is logged in */}
            {authState.isLoggedIn && (
              <button
                onClick={handleLogout}
                className="hover:text-blue-400 transition duration-200 focus:outline-none"
              >
                Logout
              </button>
            )}
          </div>

          {/* Hamburger Menu Button */}
          <div className="md:hidden">
            <button
              className="text-white hover:text-blue-400 focus:outline-none"
              onClick={toggleMenu}
            >
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

        {/* Mobile Navigation */}
        {isMenuOpen && (
          <div className="md:hidden bg-gray-700 p-4 z-40 relative">
            {/* Always visible links */}
            <Link
              to="/"
              className="block py-2 px-4 hover:bg-gray-600 rounded"
              onClick={() => setIsMenuOpen(false)}
            >
              Home
            </Link>
            <Link
              to="/blogs"
              className="block py-2 px-4 hover:bg-gray-600 rounded"
              onClick={() => setIsMenuOpen(false)}
            >
              Blogs
            </Link>
            <Link
              to="/games-overview"
              className="block py-2 px-4 hover:bg-gray-600 rounded"
              onClick={() => setIsMenuOpen(false)}
            >
              Spelkalender
            </Link>
            <Link
              to="/predict-result"
              className="block py-2 px-4 hover:bg-gray-600 rounded"
              onClick={() => setIsMenuOpen(false)}
            >
              Predict
            </Link>


            {/* Links based on user roles */}
            {authState.isLoggedIn && authState.role === "beheer" && (
              <Link
                to="/admin"
                className="block py-2 px-4 hover:bg-gray-600 rounded"
                onClick={() => setIsMenuOpen(false)}
              >
                Admin Dashboard
              </Link>
            )}
            {authState.isLoggedIn && authState.role === "user" && (
              <Link
                to="/dashboard"
                className="block py-2 px-4 hover:bg-gray-600 rounded"
                onClick={() => setIsMenuOpen(false)}
              >
                Mijn Dashboard
              </Link>
            )}

            {/* Logout link if user is logged in */}
            {authState.isLoggedIn && (
              <button
                onClick={() => {
                  handleLogout();
                  setIsMenuOpen(false); // Close the menu after logout
                }}
                className="block py-2 px-4 hover:bg-gray-600 rounded text-left w-full"
              >
                Logout
              </button>
            )}
          </div>
        )}
      </nav>
    );
  };

  export default Navbar;