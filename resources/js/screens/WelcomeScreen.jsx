import React from 'react'
import {Link, useNavigate } from 'react-router-dom'


function WelcomeScreen() {
  return (
    <div className='flex w-screen bg-purple-900 h-screen text-white flex-col justify-center items-center'>
        <h1 className='text-4xl font-bold text-orange-600' style={{ fontFamily:'fredoka One'}}>Welcome to Wallet</h1>
        <p>...payment made easy.</p>

        <div className='flex gap-x-6 mt-12'>
            <Link to="/login" className='p-3 bg-orange-600 text-white px-12 rounded-full hover:bg-violet-400'>Login</Link>
            <Link to="/register" className='p-3 bg-orange-600 text-white px-12 rounded-full hover:bg-violet-400'>Sign Up</Link>

        </div>
    </div>
  )
}

export default WelcomeScreen
