import React from 'react'
import { Link } from 'react-router-dom'


function LoginScreen() {
  return (
    <div className='bg-purple-900 w-screen h-screen text-white flex flex-col justify-center items-center'>
     <div className='w-[380px] h-[400px] bg-white text-black drop-shadow-4xl rounded-xl items-center flex flex-col p-5'>
     <h1 className='text-4xl mt-2 text-purple-900' style={{fontFamily:'fredoka One'}}>Login</h1>
      <p className='font-bold text-sm mb-2'>...alot await you.</p>
      <input type="text" placeholder="Username or Mobile" className="tracking-[-1px] font-bold p-2 ring-1 ring-slate-900/5 w-full my-4 outline-0" />
      <input type="password" placeholder="Password" className="tracking-[-1px] font-bold p-2 ring-1 ring-slate-900/5 w-full outline-0" />

<Link className='self-end mt-3 font-bold tracking-[-1px] text-purple-900 hover:text-slate-500' to="/forgot-password">Forgot your password?</Link>

        <button className='font-bold bg-purple-900 text-white w-full p-3 mt-12 rounded-md hover:bg-orange-400'>Login</button>

        <div className='flex gap-2 mt-3'>
            <p className='font-bold tracking-[-1px]'>Don't have an account?</p>
            <Link className='  font-bold tracking-[-1px] text-purple-900 hover:text-slate-500' to="/new-register"> Sign Up</Link>

        </div>



     </div>

    </div>
  )
}

export default LoginScreen
