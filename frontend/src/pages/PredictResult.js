import React, {useEffect, useState} from "react";
import Api from "../Api";

const PredictResult = () => {
    // State to store the list of matches
    const [matches, setMatches] = useState([]);

    // State to store user predictions
    const [predictions, setPredictions] = useState({});

    // State for handling the loading indicator
    const [loading, setLoading] = useState(true);

    // Fetch matches from the database/API
    useEffect(() => {
        // -------------Replace with API call to fetch matches--------------------
        const fetchMatches = async () => {
            try {
                setLoading(true);
                // Simulated API response
                const response = [
                    {id: 1, teamA: "Team A", teamB: "Team B", date: "2025-01-25"},
                    {id: 2, teamA: "Team C", teamB: "Team D", date: "2025-01-26"},
                    {id: 3, teamA: "Team E", teamB: "Team F", date: "2025-01-27"},
                ];
                setMatches(response); // Store matches in state
            } catch (error) {
                console.error("Error fetching matches:", error);
            } finally {
                setLoading(false); // Stop loading indicator
            }
        };

        fetchMatches();
    }, []);

    // Handle user input for predictions
    const handlePredictionChange = (matchId, team, value) => {
        setPredictions({
            ...predictions,
            [matchId]: {
                ...predictions[matchId],
                [team]: value,
            },
        });
    };

    // Submit predictions to the API/database
    const submitPredictions = async () => {
        try {
            const predictionsArray = Object.keys(predictions).map((matchId) => ({
                calendar_id: matchId,
                home_team_score: parseInt(predictions[matchId]?.teamA || 0, 10),
                away_team_score: parseInt(predictions[matchId]?.teamB || 0, 10),
            }));

            const response = await Api.post("/api/predictions", predictionsArray);

            alert("Predictions saved successfully!");
            console.log("Server Response:", response.data);
        } catch (error) {
            console.error("Error submitting predictions:", error);
            alert("Error submitting predictions.");
        }
    };


    return (
        <div className="min-h-screen bg-gray-800 text-white p-6 left-0 right-0 w-full opacity-90">
            {/* Page title */}
            <h1 className="text-2xl font-bold mb-6">Predict Results</h1>

            {/* Show loading spinner while fetching matches */}
            {loading ? (
                <div className="text-center text-yellow-400">Loading matches...</div>
            ) : (
                <div className="bg-gray-700 p-6 rounded shadow-md">
                    {/* List of matches */}
                    <h2 className="text-xl font-bold mb-4">Upcoming Matches</h2>
                    {matches.length === 0 ? (
                        <p className="text-gray-300">No matches available for prediction.</p>
                    ) : (
                        <table className="w-full text-left text-gray-300 border-collapse">
                            <thead>
                            <tr>
                                <th className="border-b border-gray-500 py-2">Match</th>
                                <th className="border-b border-gray-500 py-2">Date</th>
                                <th className="border-b border-gray-500 py-2">Prediction</th>
                            </tr>
                            </thead>
                            <tbody>
                            {matches.map((match) => (
                                <tr key={match.id} className="hover:bg-gray-600">
                                    <td className="py-2">
                                        {match.teamA} vs {match.teamB}
                                    </td>
                                    <td className="py-2">{match.date}</td>
                                    <td className="py-2">
                                        {/* Prediction input for Team A */}
                                        <input
                                            type="number"
                                            min="0"
                                            value={predictions[match.id]?.teamA || ""}
                                            onChange={(e) =>
                                                handlePredictionChange(match.id, "teamA", e.target.value)
                                            }
                                            placeholder="Team A"
                                            className="w-16 p-1 rounded bg-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                        />
                                        <span className="mx-2">-</span>
                                        {/* Prediction input for Team B */}
                                        <input
                                            type="number"
                                            min="0"
                                            value={predictions[match.id]?.teamB || ""}
                                            onChange={(e) =>
                                                handlePredictionChange(match.id, "teamB", e.target.value)
                                            }
                                            placeholder="Team B"
                                            className="w-16 p-1 rounded bg-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                        />
                                    </td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    )}

                    {/* Submit button */}
                    {matches.length > 0 && (
                        <div className="mt-6 text-right">
                            <button
                                onClick={submitPredictions}
                                className="px-6 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-400"
                            >
                                Submit Predictions
                            </button>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
};

export default PredictResult;
