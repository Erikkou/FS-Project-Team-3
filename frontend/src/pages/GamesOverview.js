import React, {useEffect, useState} from "react";
import Api from "../Api";

const GamesOverview = () => {
    const [currentRound, setCurrentRound] = useState(null); // Start with null
    const [roundData, setRoundData] = useState(null); // Start with null
    const [fixtures, setFixtures] = useState([]); // Fixture data
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);

    // Fetch current round (week data)
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
                setIsLoading(true);
                try {
                    const response = await Api.get(`/api/show/rounds/${currentRound}`);
                    console.log("Fetched round data:", response.data); // Log de ontvangen ronde data
                    setRoundData(response.data);

                    const fixturesResponse = await Api.get(`/calendar/round/${currentRound}`);
                    console.log("Fetched matches data:", fixturesResponse.data);
                    setFixtures(fixturesResponse.data);
                } catch (error) {
                    setError("Failed to load round data.");
                    console.error("Error fetching round data:", error);
                } finally {
                    setIsLoading(false);
                }
            };
            fetchRound();
        }
    }, [currentRound]); // Fetch round when currentRound changes

    // Go to previous round
    const goToPreviousRound = () => {
        if (roundData?.previous_round) {
            setCurrentRound(roundData.previous_round.id);
        }
    };

    // Go to next round
    const goToNextRound = () => {
        if (roundData?.next_round) {
            setCurrentRound(roundData.next_round.id);
        }
    };

    // Render loading or error message
    if (isLoading) return <p className="text-center text-white">Loading...</p>;
    if (error) return <p className="text-red-500 text-center">{error}</p>;

    return (
        <div className="flex flex-col items-center justify-start bg-transparent py-25">
            <div className="w-full max-w-6xl p-3 rounded-2xl bg-[#000033] shadow-lg mt-24">
                <h1 className="text-3xl font-bold text-white mb-2 text-center">
                    Spelkalender
                </h1>
                {roundData ? (
                    <>
                        {roundData?.current_round ? (
                            <>
                                <h2 className="text-white mb-2 text-center">
                                    Round: {roundData?.current_round?.name || "Unknown"}
                                </h2>
                                <h3 className="text-white mb-6 text-center">
                                    Date: {roundData?.current_round?.starting_at || "Unknown"} - {roundData?.current_round?.ending_at || "Unknown"}
                                </h3>
                                <div className="flex justify-between mt-4">
                                    <button
                                        onClick={goToPreviousRound}
                                        disabled={!roundData?.previous_round}
                                        className={`mx-4 px-4 py-2 text-white font-bold rounded ${roundData?.previous_round ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 cursor-not-allowed'}`}
                                    >
                                        ← Previous Round
                                    </button>
                                    <button
                                        onClick={goToNextRound}
                                        disabled={!roundData?.next_round}
                                        className={`mx-4 px-4 py-2 text-white font-bold rounded ${roundData?.next_round ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-500 cursor-not-allowed'}`}
                                    >
                                        Next Round →
                                    </button>
                                </div>
                                {/* Display Fixtures in Grid */}
                                <div className="grid grid-cols-3 gap-4 mt-6">
                                    {fixtures[currentRound]?.map((fixture) => (
                                        <div
                                            key={fixture.id}
                                            className="bg-gray-800 p-4 rounded-lg shadow-lg"
                                        >
                                            <h4 className="text-white text-center">
                                                {fixture.home_team} {fixture.home_score} vs {fixture.away_team} {fixture.away_score}
                                            </h4>
                                            <p className="text-white text-center">
                                                {new Date(fixture.date).toLocaleDateString()}
                                            </p>
                                            <p className="text-white text-center">
                                                Stadium: {fixture.stadium}
                                            </p>
                                        </div>
                                    ))}
                                </div>

                            </>
                        ) : (
                            <p className="text-white text-center">No round data available.</p>
                        )}
                    </>
                ) : (
                    <p className="text-white text-center">Loading round data...</p>
                )}
            </div>
        </div>
    );
};

export default GamesOverview;
