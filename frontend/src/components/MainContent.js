import React, {useContext, useEffect} from "react";
import {useNavigate} from "react-router-dom";
import {AuthContext} from "../AuthContext";

const MainContent = () => {
    const {authState, logout} = useContext(AuthContext);
    const navigate = useNavigate();

    useEffect(() => {
        if (!authState.isLoggedIn) {
        }
    }, [authState.isLoggedIn]);

    const handleSignUpClick = () => {
        navigate("/signup");
    };

    const handleLoginClick = () => {
        navigate("/login");
    };

    const handleLogout = () => {
        logout(); // Call logout function from context
    };

    return (
        <div className="relative flex flex-col items-center justify-center h-screen text-center left-0 right-0 w-full">
            {/* Overlay for better text visibility */}
            <div className="absolute inset-0 bg-black bg-opacity-30 left-0"></div>

            {/* Content */}
            <div className="relative z-10 space-y-8">
                {authState.isLoggedIn ? (
                    <>
                        <h1 className="text-5xl font-extrabold text-yellow-400">
                            Welkom bij <span className="text-white">Score It Too ⚽️</span>
                        </h1>
                        <div
                            className="bg-blue-950 bg-opacity-90 p-6 rounded-lg max-w-xl sm:max-w-lg mx-auto bg-opacity-50">
                            <p className="text-xl font-semibold text-gray-300 mb-4">
                                Klaar om punten te scoren? Ga naar de spelkalender en doe mee met een spel!
                            </p>
                            <p className="text-xl font-semibold text-gray-300">
                                Je kunt ook je voorspellingen bekijken en je scores inzien.
                            </p>
                        </div>
                        <div className="flex space-x-6 justify-center">
                            <button
                                className="bg-yellow-500 text-gray-800 font-bold py-3 px-8 rounded-lg hover:bg-yellow-400 transition duration-300 shadow-lg"
                                onClick={() => navigate("/games-overview")}
                            >
                                Spelkalender
                            </button>
                            <button
                                className="bg-yellow-500 text-gray-800 font-bold py-3 px-8 rounded-lg hover:bg-yellow-400 transition duration-300 shadow-lg"
                                onClick={() => navigate("/user-profile")}
                            >
                                Profiel
                            </button>
                        </div>
                    </>
                ) : (
                    <>
                        <h1 className="text-5xl font-extrabold text-yellow-400">
                            Welkom bij <span className="text-white">Score It Too ⚽️</span>
                        </h1>
                        <p className="text-xl font-semibold text-gray-300">
                            Meespelen vanaf de bank!
                        </p>
                        <p className="text-xl font-semibold text-gray-300">
                            Stel je droomteam samen, scoor punten en klim naar de top!
                        </p>
                        <div className="flex space-x-6 justify-center">
                            <button
                                className="bg-yellow-500 text-gray-800 font-bold py-3 px-8 rounded-lg hover:bg-yellow-400 transition duration-300 shadow-lg"
                                onClick={handleSignUpClick}
                            >
                                Aanmelden
                            </button>
                            <button
                                className="bg-gray-700 text-white font-bold py-3 px-8 rounded-lg hover:bg-gray-600 transition duration-300 shadow-lg"
                                onClick={handleLoginClick}
                            >
                                Log in
                            </button>
                        </div>
                    </>
                )}
            </div>
        </div>
    );
};

export default MainContent;
