import React from "react";
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";
import "./App.css";

// Components
import Navbar from "./components/Navbar";
import Footer from "./components/Footer";
import BackgroundSection from "./components/BackgroundSection";
import MainContent from "./components/MainContent";
import LoginPage from "./components/LoginPage";
import SignupPage from "./components/SignUpPage";
import UserSettings from "./components/UserSettings";

// Pages
import AdminLayout from "./pages/AdminLayout";
import LeagueManagement from "./pages/LeagueManagement";
import TeamManagement from "./pages/TeamManagement";
import UserManagement from "./pages/UserManagement";
import MatchScheduling from "./pages/MatchScheduling";
import ResultsAndStandings from "./pages/ResultsAndStandings";
import AdminBlogs from "./pages/AdminBlogs";
import BlogPage from "./pages/BlogPage";
import BlogDetails from "./pages/BlogPageDetail";
import UserDashboard from "./pages/UserDashboard";
import UserTeamManagement from "./pages/UserTeamManagement";
import UserLeagueManagement from "./pages/UserLeagueManagement";
import PredictResult from "./pages/PredictResult";
import UserStats from "./pages/UserStats";
import UserProfile from "./pages/UserProfile";
import GamesOverview from "./pages/GamesOverview";
import GameDetails from "./pages/GameDetails";


// Auth Context
import {AuthProvider} from "./AuthContext";
import LeaderboardDetailsPopup from "./pages/LeaderboardDetailsPopup";

const App = () => {
    return (
        <AuthProvider>
            <Router>
                <div className="App">
                    <Navbar/>
                    <BackgroundSection>
                        <Routes>
                            {/* Public Routes */}
                            <Route path="/" element={<MainContent/>}/>
                            <Route path="/login" element={<LoginPage/>}/>
                            <Route path="/signup" element={<SignupPage/>}/>
                            <Route path="/blogs" element={<BlogPage/>}/>
                            <Route path="/blog/show/:id" element={<BlogDetails/>}/>
                            <Route path="/games-overview" element={<GamesOverview/>}/>
                            <Route path="/game-details/:gameId" element={<GameDetails/>}/>

                            {/* Admin Routes */}
                            <Route path="/admin" element={<AdminLayout/>}/>
                            <Route path="/admin/league-management" element={<LeagueManagement/>}/>
                            <Route path="/admin/team-management" element={<TeamManagement/>}/>
                            <Route path="/admin/user-management" element={<UserManagement/>}/>
                            <Route path="/admin/match-scheduling" element={<MatchScheduling/>}/>
                            <Route path="/admin/results-and-standings" element={<ResultsAndStandings/>}/>
                            <Route path="/admin/AdminBlogs" element={<AdminBlogs/>}/>

                            {/* User Routes */}
                            <Route path="/dashboard" element={<UserDashboard/>}/>
                            <Route path="/settings" element={<UserSettings/>}/>
                            <Route path="/user-team-management/:gameId" element={<UserTeamManagement/>}/>
                            <Route path="/user-league-management" element={<UserLeagueManagement/>}/>
                            <Route path="/leaderboard/:teamId" element={<LeaderboardDetailsPopup/>}/>
                            <Route path="/predict-result" element={<PredictResult/>}/>
                            <Route path="/user/stats" element={<UserStats/>}/>
                            <Route path="/user-profile" element={<UserProfile/>}/>
                        </Routes>
                    </BackgroundSection>
                    <Footer/>
                </div>
            </Router>
        </AuthProvider>
    );
};

export default App;
