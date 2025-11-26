<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Domaine;
use App\Entity\Protocole;
use App\Entity\Rubrique;
use App\Entity\Theme;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création des Domaines
        $domaineGynecologie = new Domaine();
        $domaineGynecologie->setNom('Gynécologie');
        $domaineGynecologie->setDescription('La gynécologie est une spécialité médicale dédiée à la santé de la femme, en particulier au fonctionnement et aux maladies de l’appareil reproducteur féminin (utérus, ovaires, trompes, vagin). Elle inclut le suivi des cycles menstruels, la contraception, la grossesse, le dépistage et le traitement des infections ou cancers gynécologiques.');
        $manager->persist($domaineGynecologie);

        $domainePerinatalite = new Domaine();
        $domainePerinatalite->setNom('Périnatalité');
        $domainePerinatalite->setDescription('La périnatalité regroupe l’ensemble des soins, suivis et accompagnements autour de la grossesse, de l’accouchement et des premières semaines de vie du nouveau-né. Elle vise à assurer la santé et le bien-être de la mère, du bébé et de la famille durant cette période clé.');
        $manager->persist($domainePerinatalite);


        // Création des Rubriques
        $rubriqueAntenatal = new Rubrique();
        $rubriqueAntenatal->setNom('Anténatal');
        $rubriqueAntenatal->setDescription('Protocoles concernant la période prénatale et les soins avant la naissance');
        $rubriqueAntenatal->addDomaine($domaineGynecologie);
        $manager->persist($rubriqueAntenatal);

        $rubriqueAccouchement = new Rubrique();
        $rubriqueAccouchement->setNom('Accouchement');
        $rubriqueAccouchement->setDescription('Protocoles liés à l\'accouchement');
        $rubriqueAccouchement->addDomaine($domaineGynecologie);
        $manager->persist($rubriqueAccouchement);

        $rubriqueNeonatal = new Rubrique();
        $rubriqueNeonatal->setNom('Néonatal');
        $rubriqueNeonatal->setDescription('Protocoles concernant la période néonatale et les soins au nouveau-né');
        $rubriqueNeonatal->addDomaine($domainePerinatalite);
        $manager->persist($rubriqueNeonatal);

        $rubriquePostnatal = new Rubrique();
        $rubriquePostnatal->setNom('Postnatal');
        $rubriquePostnatal->setDescription('Protocoles concernant la période postnatale et les soins après la naissance');
        $rubriquePostnatal->addDomaine($domainePerinatalite);
        $manager->persist($rubriquePostnatal);

        $rubriqueIVG = new Rubrique();
        $rubriqueIVG->setNom('Interruption Volontaire de Grossesse');
        $rubriqueIVG->setDescription('Protocoles concernant l\'interruption volontaire de grossesse');
        $rubriqueIVG->addDomaine($domaineGynecologie);
        $manager->persist($rubriqueIVG);

        $rubriqueContraception = new Rubrique();
        $rubriqueContraception->setNom('Contraception');
        $rubriqueContraception->setDescription('Protocoles concernant la contraception');
        $rubriqueContraception->addDomaine($domainePerinatalite);
        $manager->persist($rubriqueContraception);

        $rubriqueIST = new Rubrique();
        $rubriqueIST->setNom('Infection sexuellement transmissible');
        $rubriqueIST->setDescription('Protocoles concernant les infections sexuellement transmissibles');
        $rubriqueIST->addDomaine($domainePerinatalite);
        $manager->persist($rubriqueIST);

        // Création des Thèmes
        $themeRCIU = new Theme();
        $themeRCIU->setNom('Retard de croissance intra-utérin');
        $themeRCIU->setRubrique($rubriqueNeonatal);
        $manager->persist($themeRCIU);

        $themeMIN = new Theme();
        $themeMIN->setNom('Mort Innatendu du Nourrisson');
        $themeMIN->setRubrique($rubriquePostnatal);
        $manager->persist($themeMIN);

        $themeViolences = new Theme();
        $themeViolences->setNom('Dépistage des violences');
        $themeViolences->setRubrique($rubriquePostnatal);
        $manager->persist($themeViolences);

        $themeTabac = new Theme();
        $themeTabac->setNom('Tabac chez la femme enceinte');
        $themeTabac->setRubrique($rubriqueAntenatal);
        $manager->persist($themeTabac);

        $themeVaccination = new Theme();
        $themeVaccination->setNom('Vaccination et grossesse');
        $themeVaccination->setRubrique($rubriqueAntenatal);
        $manager->persist($themeVaccination);

        $themeDiabete = new Theme();
        $themeDiabete->setNom('Diabète gestationnel');
        $themeDiabete->setRubrique($rubriqueAntenatal);
        $manager->persist($themeDiabete);

        $themeT21 = new Theme();
        $themeT21->setNom('Dépistage de la Trisomie 21');
        $themeT21->setRubrique($rubriqueAntenatal);
        $manager->persist($themeT21);

        $themePE = new Theme();
        $themePE->setNom('Prééclampsie');
        $themePE->setRubrique($rubriqueAccouchement);
        $manager->persist($themePE);

        // Création des Protocoles
        $protocoleRCIU = new Protocole();
        $protocoleRCIU->setNom('Protocole retard de croissance intra-utérin');
        $protocoleRCIU->setTheme($themeRCIU);
        $protocoleRCIU->setFichier('protocole_retard_croissance_intra_uterin.pdf');
        $manager->persist($protocoleRCIU);

        $protocoleMIN = new Protocole();
        $protocoleMIN->setNom('Protocole Mort Innatendu du Nourrisson');
        $protocoleMIN->setTheme($themeMIN);
        $protocoleMIN->setFichier('protocole_mort_innatendu_nourrisson.pdf');
        $manager->persist($protocoleMIN);

        $protocoleViolences = new Protocole();
        $protocoleViolences->setNom('Protocole dépistage des violences');
        $protocoleViolences->setTheme($themeViolences);
        $protocoleViolences->setFichier('protocole_dépistage_violences.pdf');
        $manager->persist($protocoleViolences);

        $protocoleTabac = new Protocole();
        $protocoleTabac->setNom('Protocole prise en charge du tabac chez la femme enceinte');
        $protocoleTabac->setTheme($themeTabac);
        $protocoleTabac->setFichier('protocole_tabac_femme_enceinte.pdf');
        $manager->persist($protocoleTabac);

        $protocoleVaccination = new Protocole();
        $protocoleVaccination->setNom('Protocole vaccination et grosssesse');
        $protocoleVaccination->setTheme($themeVaccination);
        $protocoleVaccination->setFichier('protocole_vaccination.pdf');
        $manager->persist($protocoleVaccination);

        $protocoleDiabete = new Protocole();
        $protocoleDiabete->setNom('Protocole dépistage du diabète gestationnel');
        $protocoleDiabete->setTheme($themeDiabete);
        $protocoleDiabete->setFichier('protocole_diabete_gestationnel.pdf');
        $manager->persist($protocoleDiabete);

        $protocoleT21 = new Protocole();
        $protocoleT21->setNom('Protocole dépistage de la Trisomie 21');
        $protocoleT21->setTheme($themeT21);
        $protocoleT21->setFichier('protocole_trisomie_21.pdf');
        $manager->persist($protocoleT21);

        $protocolePE = new Protocole();
        $protocolePE->setNom('Protocole prééclampsie');
        $protocolePE->setTheme($themePE);
        $protocolePE->setFichier('protocole_pre_eclampsie.pdf');
        $manager->persist($protocolePE);

        // Création d'un Utilisateur standard
        $utilisateur = new Utilisateur();
        $utilisateur->setNom('GAUCHER');
        $utilisateur->setPrenom('Catherine');
        $utilisateur->setEmail('user.user@hopital.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, 'user');
        $utilisateur->setPassword($hashedPassword);
        $utilisateur->setRoles(['ROLE_USER']);
        $manager->persist($utilisateur);

        // Création d'un Admin
        $admin = new Admin();
        $admin->setNom('SAUVAGE');
        $admin->setPrenom('Maxime');
        $admin->setEmail('admin.admin@hopital.fr');
        $hashedPasswordAdmin = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setPassword($hashedPasswordAdmin);
        $manager->persist($admin);

        // Enregistrement de toutes les entités
        $manager->flush();
    }
}
