/*
 * oj7-cjudge
 * Source code
 * Code by CDQZ_laekov
 * Version on linux
 * Last change on 01/11 2015
 */

#include "acejudge.h"
#include "cont.h"

const int max_user = 1024;

int tu;
user ul[max_user];

void load_users(int cid) {
	tu = 0;
	char fln[max_path], tmpstr[max_path];
	sprintf(fln, "./upload/%08d/uid.list", cid);
	FILE* ipf = fopen(fln, "r");
	if (!ipf)
		return;
	while (fscanf(ipf, "%s", tmpstr) != EOF) {
		ul[tu]. uid = new char[strlen(tmpstr) + 3];
		strcpy(ul[tu]. uid, tmpstr);
	//	printf("Get user: %s\n", ul[tu]. uid);
		++ tu;
	}
	fclose(ipf);
	//printf("Tot user: %d\n", tu);
}

void ref_users(int cid) {
	for (int i = 0; i < tu; ++ i) {
		char upath[max_path], fln[max_path];
		sprintf(upath, "./upload/%08d/%s", cid, ul[i]. uid);
		ul[i]. tot_sco = 0;
		if (access(upath, 0) == -1) {
			for (int j = 0; j < 4; ++ j)
				ul[i]. sco[j] = 0;
		}
		else {
			for (int j = 0; j < 4; ++ j) {
				sprintf(fln, "%s/.ajtest/%c.rs", upath, j + 97);
				FILE* ipf = fopen(fln, "r");
				if (!ipf)
					continue;
				fscanf(ipf, "%*s%d", ul[i]. sco + j);
				fclose(ipf);
				ul[i]. tot_sco += ul[i]. sco[j];
			}
		}
	}
}

void clear_res(int cid, char* uid, int pid) {
	char od[max_path];
	sprintf(od, "./upload/%08d/%s/.ajtest/%c.rs", cid, uid, pid);
	if (access(od, 0) == 0) {
		sprintf(od, "rm ./upload/%08d/%s/.ajtest/%c.rs", cid, uid, pid);
		system(od);
	}
}

void ref_ulist(int cid) {
	char fln[max_path];
	sprintf(fln, "./upload/%08d/uid.list", cid);
	FILE* opf = fopen(fln, "w");
	if (!opf)
		return;
	for (int i = 0; i < tu; ++ i)
		fprintf(opf, "%s\n", ul[i]. uid);
	fclose(opf);
}

void make_res(int cid) {
	char fln[max_path], tmpstr[max_path], pid[4][max_path];
	for (int i = 0; i < 4; ++ i) {
		sprintf(fln, "./data/%08d/%c.cfg", cid, i + 97);
		FILE* ipf = fopen(fln, "r");
		if (ipf) {
			fscanf(ipf, "%s", pid[i]);
			fclose(ipf);
		}
		else {
			sprintf(pid[i], "Problem %c", i + 97);
		}
	}
	sprintf(fln, "./results/%08d.rl", cid);
	FILE* opf = fopen(fln, "w");
	fprintf(opf, "%08d\n%d\n", cid, tu);
	for (int i = 0, sl = 0x3f3f3f3f, rk = 0; i < tu; ++ i) {
		if (ul[i]. tot_sco != sl) {
			sl = ul[i]. tot_sco;
			rk = i + 1;
		}
		fprintf(opf, "%s %d %d\n", ul[i]. uid, ul[i]. tot_sco, rk);
	}
	fclose(opf);
}

int totprob(int cid) {
	char fln[max_path], ite[max_path];
	sprintf(fln, "./data/%08d/.contcfg", cid);
	int ret(3);
	FILE* ipf(fopen(fln, "r"));
	while (fscanf(ipf, "%s", ite) != EOF)
		if (!strcmp(ite, "totprob"))
			fscanf(ipf, "%d", &ret);
	fclose(ipf);
	return ret;
}

int main(int argc, char* args[]) {
	while (access(".cjudgerunning", 0) != -1);
	system("echo >.cjudgerunning");
	int cid = getcid();
	int pid = -1;
	int tot_prob = totprob(cid);
	for (int i = 2; i < argc; ++ i)
		if (args[i][0] == '-') {
			if (!strcmp(args[i] + 1, "help")) {
				show_help();
				return 0;
			}
			else if (!strcmp(args[i] + 1, "version")) {
				show_version();
				return 0;
			}
			else if (!strcmp(args[i] + 1, "cid")) {
				sscanf(args[i + 1], "%d", &cid);
				++ i;
			}
			else if (!strcmp(args[i] + 1, "pid")) {
				sscanf(args[i + 1], "%d", &pid);
				++ i;
			}
		}
	load_users(cid);
	printf("%d %d\n", cid, pid);
	if (!strcmp(args[1], "run")) {
		bool paused = 0;
		for (int i = 0; i < tu && !paused; ++ i)
			for (int j = 0; j < tot_prob && !paused; ++ j)
				clear_res(cid, ul[i]. uid, j + 97);
		for (int i = 0; i < tu && !paused; ++ i)
			for (int j = 0; j < tot_prob && !paused; ++ j) {
				char od[max_path];
				sprintf(od, "oj7-judge ./upload/%08d/%s ./data/%08d %c", cid, ul[i]. uid, cid, j + 97);
				system(od);
				if (access(".cjudgerunning", 0) == -1)
					paused = 1;
			}
		if (paused) {
			puts("Stopped");
		}
		else {
			ref_users(cid);
			sort(ul, ul + tu, cmpUser);
			ref_ulist(cid);
			make_res(cid);
		}
	}
	else if (!strcmp(args[1], "res")) {
		ref_users(cid);
		sort(ul, ul + tu, cmpUser);
		ref_ulist(cid);
		make_res(cid);
	}
	else if (!strcmp(args[1], "rejudgeu") || !strcmp(args[1], "ioijudge")) {
		char uid[max_path];
		if (args[2])
			strcpy(uid, args[2]);
		else
			return 1;
		int bi(0), ei(tot_prob);
		if (pid > -1)
			bi = pid, ei = pid + 1;
		for (int j = bi; j < ei; ++ j)
			clear_res(cid, uid, j + 97);
		for (int j = bi; j < ei; ++ j) {
			char od[max_path];
			sprintf(od, "oj7-judge ./upload/%08d/%s ./data/%08d %c", cid, uid, cid, j + 97);
			system(od);
		}
		ref_users(cid);
		sort(ul, ul + tu, cmpUser);
		ref_ulist(cid);
		make_res(cid);
	}
	else if (!strcmp(args[1], "cor")) {
		char uid[max_path];
		if (args[2])
			strcpy(uid, args[2]);
		else
			return 1;
		for (int j = 0; j < tot_prob; ++ j)
			clear_res(cid, uid, j + 97);
		for (int j = 0; j < tot_prob; ++ j) {
			char od[max_path];
			sprintf(od, "oj7-judge ./upload/%08d/%s ./data/%08d %c", cid, uid, cid, j + 97);
			system(od);
		}
	}
	else if (!strcmp(args[1], "rejudgep")) {
		if (!args[2])
			return 1;
		int j = args[2][0] - 97;
		bool paused = 0;
		for (int i = 0; i < tu; ++ i)
			clear_res(cid, ul[i]. uid, j + 97);
		for (int i = 0; i < tu && !paused; ++ i) {
			char od[max_path];
			sprintf(od, "oj7-judge ./upload/%08d/%s ./data/%08d %c", cid, ul[i]. uid, cid, j + 97);
			system(od);
			if (access(".cjudgerunning", 0) == -1)
				paused = 1;
		}
		if (paused) {
			puts("Stopped");
		}
		else {
			ref_users(cid);
			sort(ul, ul + tu, cmpUser);
			ref_ulist(cid);
			make_res(cid);
		}
	}
	if (access(".cjudgerunning", 0) != -1)
		system("rm .cjudgerunning");
}

