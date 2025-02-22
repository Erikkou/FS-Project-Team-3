import React, {useEffect, useState} from "react";
import {useNavigate} from "react-router-dom";
import Api from "../Api";

const UserProfile = () => {
    // **State to store user profile data**
    const [user, setUser] = useState(null);
    const [email, setEmail] = useState('');
    const navigate = useNavigate();

    useEffect(() => {
        const fetchUser = async () => {
            try {
                const response = await Api.get('/api/me');
                setUser(response.data);
                setEmail(response.data.email);
            } catch (error) {
                localStorage.removeItem('token');
                navigate('/login');
            }
        };
        fetchUser().then(r => r);
    }, [navigate]);

    const handleEmailChange = (e) => {
        setEmail(e.target.value);
    };

    const handleEmailUpdate = async (e) => {
        e.preventDefault();
        try {
            await Api.put('/api/users/email', {email});
            alert('Email updated successfully');
        } catch (error) {
            alert('Failed to update email');
        }
    };

    if (!user) {
        return <div>Loading...</div>;
    }

    return (
        <div className="min-h-screen bg-gray-800 text-white p-6 left-0 right-0 w-full opacity-90">
            {/* Page Title */}
            <h1 className="text-3xl font-bold mb-6">User Profile</h1>

            {/* User Profile Card */}
            <div className="p-6 bg-gray-700 rounded shadow-lg max-w-3xl mx-auto">
                {/* Profile Header */}
                <div className="flex items-center mb-6">
                    {/* User Avatar */}
                    {/*<img*/}
                    {/*  src={userData.avatar}*/}
                    {/*  alt="User Avatar"*/}
                    {/*  className="w-20 h-20 rounded-full border-2 border-yellow-500"*/}
                    {/*/>*/}
                    <div className="ml-6">
                        {/* User Name */}
                        <h3 className="text-2xl font-bold !text-white">Username: {user.username}</h3>
                        {/* User Email */}
                        <h3 className="text-gray-300">Email: {user.email}</h3>
                    </div>
                </div>

                {/* Email Update Form */}
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