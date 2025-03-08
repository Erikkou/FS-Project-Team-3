import React, {useEffect, useState} from 'react';
import Api from "../Api";

const UserScoresPage = () => {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchUsers = async () => {
            try {
                const response = await Api.get('api/users/scores');
                setUsers(response.data);
            } catch (error) {
                console.error('Error fetching users:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchUsers();
    }, []);

    return (
        <div className="min-h-screen bg-gray-800 text-white p-6 left-0 right-0 w-full opacity-90">
            {/* Page title */}
            <h1 className="text-2xl font-bold mb-6">User Scores</h1>

            {/* Show loading spinner while fetching users */}
            {loading ? (
                <div className="text-center text-yellow-400">Loading scores...</div>
            ) : (
                <div className="bg-gray-700 p-6 rounded shadow-md">
                    {/* List of users */}
                    <h2 className="text-xl font-bold mb-4">Scores</h2>
                    {users.length === 0 ? (
                        <p className="text-gray-300">No user scores available.</p>
                    ) : (
                        <table className="w-full text-left text-gray-300 border-collapse">
                            <thead>
                            <tr>
                                <th className="border-b border-gray-500 py-2">Users</th>
                                <th className="border-b border-gray-500 py-2">Scores</th>
                            </tr>
                            </thead>
                            <tbody>
                            {users.map((user) => (
                                <tr key={user.username} className="hover:bg-gray-600">
                                    <td className="py-2">{user.username}</td>
                                    <td className="py-2">{user.scores}</td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    )}
                </div>
            )}
        </div>
    );
};

export default UserScoresPage;