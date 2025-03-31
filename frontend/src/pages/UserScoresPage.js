import React, {useEffect, useState} from 'react';
import Api from "../Api";

const UserScoresPage = () => {
    const [users, setUsers] = useState([]);
    const [predictions, setPredictions] = useState([]);
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

        const fetchPredictions = async () => {
            try {
                const response = await Api.get('api/user/predictions');
                setPredictions(response.data);
            } catch (error) {
                console.error('Error fetching predictions:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchUsers();
        fetchPredictions();
    }, []);

    return (
        <div className="min-h-screen bg-gray-800 text-white p-6 left-0 right-0 w-full opacity-90">
            <h1 className="text-2xl font-bold mb-6">User Scores</h1>

            {loading ? (
                <div className="text-center text-yellow-400">Loading scores...</div>
            ) : (
                <div className="bg-gray-700 p-6 rounded shadow-md mb-6">
                    {users.length === 0 ? (
                        <p className="text-gray-300">No user scores available.</p>
                    ) : (
                        <table className="w-full text-left text-gray-300 border-collapse">
                            <thead>
                            <tr>
                                <th className="border-b border-gray-500 py-2">Users</th>
                                <th className="border-b border-gray-500 py-2">Total Scores</th>
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

            <h2 className="text-xl font-bold mb-4">Voorspellingen</h2>
            {loading ? (
                <div className="text-center text-yellow-400">Loading predictions...</div>
            ) : (
                <div className="bg-gray-700 p-6 rounded shadow-md">
                    {predictions.length === 0 ? (
                        <p className="text-gray-300">Geen voorspellingen gevonden.</p>
                    ) : (
                        <table className="w-full text-left text-gray-300 border-collapse">
                            <thead>
                            <tr>
                                <th className="border-b border-gray-500 py-2">Wedstrijd</th>
                                <th className="border-b border-gray-500 py-2">Voorspelling</th>
                                <th className="border-b border-gray-500 py-2">Scores</th>
                            </tr>
                            </thead>
                            <tbody>
                            {predictions.map((prediction) => (
                                <tr key={prediction.match.id} className="hover:bg-gray-600">
                                    <td className="py-2">
                                        {prediction.match.home_team} vs {prediction.match.away_team}
                                    </td>
                                    <td className="py-2">
                                        {prediction.home_team_score} - {prediction.away_team_score}
                                    </td>
                                    <td className="py-2">
                                        {prediction.points}
                                    </td>
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
