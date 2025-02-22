// import React, {useEffect, useState} from 'react';
// import {useNavigate} from 'react-router-dom';
// import Api from '../Api';
//
// // const Profile = () => {
// //     const [user, setUser] = useState(null);
// //     const navigate = useNavigate();
// //
// //     useEffect(() => {
// //         const fetchUser = async () => {
// //             try {
// //                 const response = await Api.get('/api/me');
// //                 setUser(response.data);
// //             } catch (error) {
// //                 localStorage.removeItem('token');
// //                 navigate('/login');
// //             }
// //         };
// //         fetchUser().then(r => r);
// //     }, [navigate]);
//
//     return (
//         <div>
//             <h1>Profiel</h1>
//             {user ? (
//                 <>
//                     <p>Username: {user.username}</p>
//                     <p>Email: {user.email}</p>
//                 </>
//             ) : (
//                 <p>Loading...</p>
//             )}
//         </div>
//     );
// };
//
// export default Profile;
