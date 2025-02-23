import React, {useEffect, useRef, useState} from "react";
import {useNavigate} from "react-router-dom";
import Api from "../Api";

const UserProfile = () => {
    const [user, setUser] = useState(null);
    const [email, setEmail] = useState('');
    const [avatar, setAvatar] = useState('');
    const navigate = useNavigate();
    const fileInputRef = useRef(null);
    const baseURL = process.env.REACT_APP_API_BASE_URL

    useEffect(() => {
        const fetchUser = async () => {
            try {
                const response = await Api.get('/api/me');
                setUser(response.data);
                setEmail(response.data.email);

                console.log("Avatar URL ontvangen bij page load:", response.data.avatar);

                const avatarUrl = response.data.avatar
                    ? `${baseURL}${response.data.avatar}`
                    : "https://robohash.org/johndoe";

                setAvatar(avatarUrl);
            } catch (error) {
                console.error("Fout bij ophalen van gebruiker:", error);
                localStorage.removeItem('token');
                navigate('/login');
            }
        };
        fetchUser();
    }, [navigate]);

    const handleEmailChange = (e) => {
        setEmail(e.target.value);
    };

    const handleEmailUpdate = async (e) => {
        e.preventDefault();
        try {
            const response = await Api.put('/api/users/email', {email});

            setUser(prevUser => ({
                ...prevUser,
                email: email
            }));

            alert('Email succesvol bijgewerkt!');
        } catch (error) {
            alert('Fout bij het bijwerken van email');
            console.error(error);
        }
    };


    const handleFileChange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append("avatar", file);

        console.log("Verzonden FormData:");
        for (let pair of formData.entries()) {
            console.log(pair[0], pair[1]);
        }

        try {
            const response = await Api.post('/api/users/avatar', formData);


            const userResponse = await Api.get('/api/me');
            setUser(userResponse.data);

            setAvatar(`${baseURL}${userResponse.data.avatar}?t=${new Date().getTime()}`);

            alert("Avatar succesvol bijgewerkt!");
        } catch (error) {
            console.error("Upload fout:", error.response?.status, error.response?.data);
            alert("Fout bij uploaden van avatar");
        }
    };

    if (!user) {
        return <div className="text-white text-center mt-10">Loading...</div>;
    }

    return (
        <div className="min-h-screen bg-gray-800 text-white p-6 left-0 right-0 w-full opacity-90">
            <h1 className="text-3xl font-bold mb-6">Gebruikersprofiel</h1>
            <div className="p-6 bg-gray-700 rounded shadow-lg max-w-3xl mx-auto">
                {/* Avatar met klikbare functie */}
                <div className="flex items-center mb-6">
                    <div className="relative">
                        <img
                            src={avatar}
                            alt="Gebruikers Avatar"
                            className="w-20 h-20 rounded-full border-2 border-yellow-500 cursor-pointer object-cover"
                            onClick={() => fileInputRef.current && fileInputRef.current.click()}
                        />
                        <input
                            type="file"
                            ref={fileInputRef}
                            onChange={handleFileChange}
                            style={{display: 'none'}}
                            accept="image/*"
                        />
                    </div>
                    <div className="ml-6">
                        <h3 className="text-2xl font-bold text-white">Welkom {user.username}</h3>
                        <h3 className="text-gray-300">Email: {user.email}</h3>
                    </div>
                </div>

                {/* Email bijwerken */}
                <form onSubmit={handleEmailUpdate}>
                    <div className="mb-4">
                        <label className="block text-gray-300 mb-2" htmlFor="email">Update Email</label>
                        <input
                            type="email"
                            id="email"
                            value={email}
                            onChange={handleEmailChange}
                            className="w-full p-2 rounded bg-gray-600 text-white"
                        />
                    </div>
                    <button type="submit"
                            className="px-6 py-2 bg-yellow-500 text-black font-bold rounded hover:bg-yellow-400">
                        Update Email
                    </button>
                </form>
            </div>
        </div>
    );
};

export default UserProfile;
