import React, {useEffect, useState} from "react";
import Api from "../Api";

const PredictResult = () => {
    const [currentRound, setCurrentRound] = useState([]);
    const [matches, setMatches] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [predictions, setPredictions] = useState({});

    useEffect(() => {
        const fetchCurrentRound = async () => {
            try {
                const response = await Api.get(`/api/show/rounds/week`);
                console.log("Fetched current round data:", response.data); // Log de ontvangen data
                setCurrentRound(response.data.id); // Start with the current round ID
            } catch (error) {
                setError("Failed to find the current round.");
                console.error("Error fetching current round:", error);
            }
        };
        fetchCurrentRound();
    }, []);

    // Fetch round details when currentRound changes
    useEffect(() => {
        console.log("Current Round:", currentRound); // Log de currentRound state
        if (currentRound !== null) {
            const fetchRound = async () => {
                setLoading(true);
                try {
                    const fixturesResponse = await Api.get(`/calendar/round/${currentRound}`);
                    console.log("Fetched matches data:", fixturesResponse.data);
                    setMatches(fixturesResponse.data);
                } catch (error) {
                    setError("Failed to load matches data.");
                    console.error("Error fetching matches data:", error);
                } finally {
                    setLoading(false);
                }
            };
            fetchRound();
        }
    }, [currentRound]); // Fetch round when currentRound changes

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

    const submitPredictions = async () => {

        // Controleer of alle voorspellingen zijn ingevuld
        const incompletePredictions = Object.keys(predictions).some((matchId) => {
            return (
                predictions[matchId]?.teamA === undefined || predictions[matchId]?.teamA === "" ||
                predictions[matchId]?.teamB === undefined || predictions[matchId]?.teamB === ""
            );
        });

        if (incompletePredictions) {
            alert("Vul alle voorspellingen in voordat je ze indient!");
            return;
        }

        try {
            const predictionsArray = Object.keys(predictions).map((matchId) => ({
                calendar_id: matchId,
                home_team_score: parseInt(predictions[matchId]?.teamA, 10),
                away_team_score: parseInt(predictions[matchId]?.teamB, 10),
            }));

            const response = await Api.post("/api/predictions", predictionsArray);

            alert("Alle voorspellingen zijn succesvol opgeslagen!");
            console.log("Server Response:", response.data);
        } catch (error) {
            console.error("Fout bij het indienen van voorspellingen:", error);
            alert("Er is een fout opgetreden bij het indienen van de voorspellingen.");
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
                            {matches[currentRound]?.map((match) => {
                                const matchDate = new Date(match.date);
                                const isPast = matchDate < new Date(); // Controleer of de wedstrijddatum in het verleden ligt
                                return (
                                    <tr key={match.id} className="hover:bg-gray-600">
                                        <td className="py-2">
                                            {match.home_team} vs {match.away_team}
                                        </td>
                                        <td className="py-2">{matchDate.toLocaleDateString()}</td>
                                        <td className="py-2">
                                            {/* Als de wedstrijddatum in het verleden ligt, zet de invoervelden op "disabled" */}
                                            <input
                                                type="number"
                                                min="0"
                                                value={predictions[match.id]?.teamA || ""}
                                                onChange={(e) =>
                                                    handlePredictionChange(match.id, "teamA", e.target.value)
                                                }
                                                placeholder="Team A"
                                                disabled={isPast}
                                                className={`w-16 p-1 rounded bg-gray-600 text-white focus:outline-none focus:ring-2 ${
                                                    predictions[match.id]?.teamA === "" ? "border-red-500 border-2" : "focus:ring-yellow-500"
                                                } ${isPast ? "bg-gray-500 cursor-not-allowed" : ""}`}
                                            />
                                            <span className="mx-2">-</span>
                                            <input
                                                type="number"
                                                min="0"
                                                value={predictions[match.id]?.teamB || ""}
                                                onChange={(e) =>
                                                    handlePredictionChange(match.id, "teamB", e.target.value)
                                                }
                                                placeholder="Team B"
                                                disabled={isPast}
                                                className={`w-16 p-1 rounded bg-gray-600 text-white focus:outline-none focus:ring-2 ${
                                                    predictions[match.id]?.teamB === "" ? "border-red-500 border-2" : "focus:ring-yellow-500"
                                                } ${isPast ? "bg-gray-500 cursor-not-allowed" : ""}`}
                                            />
                                        </td>
                                    </tr>
                                );
                            })}
                            </tbody>

                        </table>
                    )}

                    {/* Submit button */}
                    <div className="mt-6 text-right">
                        <button
                            onClick={submitPredictions}
                            className="px-6 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-400"
                        >
                            Submit Predictions
                        </button>
                    </div>
                </div>
            )}
        </div>

    );
};

export default PredictResult;
