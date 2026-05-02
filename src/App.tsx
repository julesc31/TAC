import { useState, useEffect } from 'react';
import Header from './components/Header';
import HomePage from './pages/HomePage';
import ClubPage from './pages/ClubPage';
import DisciplinesPage from './pages/DisciplinesPage';
import CalendrierPage from './pages/CalendrierPage';
import RejoindreePage from './pages/RejoindreePage';
import TraditionsPage from './pages/TraditionsPage';
import AlbumPhotoPage from './pages/AlbumPhotoPage';
import MentionsLegalesPage from './pages/MentionsLegalesPage';
import PolitiqueConfidentialitePage from './pages/PolitiqueConfidentialitePage';
import AdminLoginPage from './pages/AdminLoginPage';
import AdminPage from './pages/AdminPage';
import Footer from './components/Footer';
import { api } from './lib/api';

export type Page =
  | 'accueil'
  | 'club'
  | 'album-photo'
  | 'disciplines'
  | 'calendrier'
  | 'rejoindre'
  | 'traditions'
  | 'mentions-legales'
  | 'politique-confidentialite'
  | 'admin';

function App() {
  const [currentPage, setCurrentPage] = useState<Page>('accueil');
  const [loggedIn, setLoggedIn] = useState<boolean | null>(null);

  useEffect(() => {
    api.auth.check().then(({ loggedIn: li }) => setLoggedIn(li));
  }, []);

  const navigate = (page: Page) => {
    setCurrentPage(page);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const isAdminPage = currentPage === 'admin';

  const renderPage = () => {
    if (isAdminPage) {
      if (loggedIn === null) return null;
      if (!loggedIn) return <AdminLoginPage onLogin={() => setLoggedIn(true)} />;
      return (
        <AdminPage
          onLogout={() => {
            setLoggedIn(false);
            navigate('accueil');
          }}
        />
      );
    }
    switch (currentPage) {
      case 'accueil':                   return <HomePage navigate={navigate} />;
      case 'club':                      return <ClubPage />;
      case 'album-photo':               return <AlbumPhotoPage />;
      case 'disciplines':               return <DisciplinesPage />;
      case 'calendrier':                return <CalendrierPage />;
      case 'rejoindre':                 return <RejoindreePage navigate={navigate} />;
      case 'traditions':                return <TraditionsPage />;
      case 'mentions-legales':          return <MentionsLegalesPage />;
      case 'politique-confidentialite': return <PolitiqueConfidentialitePage />;
      default:                          return <HomePage navigate={navigate} />;
    }
  };

  return (
    <div className="min-h-screen flex flex-col bg-[#0a2744]">
      {!isAdminPage && <Header currentPage={currentPage} navigate={navigate} />}
      <main className="flex-1">
        {renderPage()}
      </main>
      {!isAdminPage && <Footer navigate={navigate} />}
      {isAdminPage && loggedIn && (
        <footer className="py-4 text-center border-t border-white/5">
          <button
            onClick={() => navigate('accueil')}
            className="text-stone-600 hover:text-stone-400 text-xs transition-colors"
          >
            Retour au site public
          </button>
        </footer>
      )}
    </div>
  );
}

export default App;
